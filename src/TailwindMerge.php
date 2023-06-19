<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge;

use Closure;
use YieldStudio\TailwindMerge\Interfaces\TailwindMergePlugin;

final class TailwindMerge
{
    private const SPLIT_CLASSES_REGEX = '/\s+/';

    private const IMPORTANT_MODIFIER = '!';

    private LruCache $cache;

    private ClassUtils $classUtils;

    private TailwindMergeConfig $config;

    private static ?TailwindMerge $instance = null;

    public function __construct(?TailwindMergeConfig $config = null)
    {
        $this->config = $config ?? TailwindMergeConfig::default();
        $this->classUtils = new ClassUtils($this->config);

        $this->cache = new LruCache($this->config->cacheSize);
    }

    /**
     * @param string|array<string>|array<string|bool> ...$classList
     */
    public function merge(string|array ...$classList): string
    {
        $classList = $this->join(...$classList);

        $cachedResult = $this->cache->get($classList);
        if ($cachedResult) {
            return $cachedResult;
        }

        $result = $this->mergeClassList($classList);
        $this->cache->set($classList, $result);

        return $result;
    }

    public function extend(Closure|TailwindMergePlugin ...$plugins): TailwindMerge
    {
        foreach ($plugins as $plugin) {
            $this->config = $plugin($this->config);
        }

        $this->classUtils = new ClassUtils($this->config);
        $this->cache = new LruCache($this->config->cacheSize);

        return $this;
    }

    public static function instance(): TailwindMerge
    {
        if (!self::$instance) {
            self::$instance = new TailwindMerge();
        }

        return self::$instance;
    }

    private function mergeClassList(string $classList): string
    {
        $classes = (array)preg_split(self::SPLIT_CLASSES_REGEX, trim($classList));

        $classes = array_map([$this, 'determineClassContext'], $classes);

        // Last class in conflict wins, so we need to filter conflicting classes in reverse order.
        $classes = array_reverse($classes);

        $classes = $this->filterConflictingClasses($classes);

        $classes = array_map(fn (ClassContext $context) => $context->originalClassName, $classes);

        // Reorder in the good way
        $classes = array_reverse($classes);

        return implode(' ', $classes);
    }

    /**
     * @param string|array ...$classList
     * @return string
     */
    private function join(string|array ...$classList): string
    {
        $output = [];

        foreach ($classList as $item) {
            if (is_string($item)) {
                $output[] = $item;
            }

            if (is_array($item)) {
                foreach ($item as $class => $constraint) {
                    if (is_numeric($class)) {
                        $output[] = $constraint;
                    } elseif ($constraint) {
                        $output[] = $class;
                    }
                }
            }
        }

        return implode(' ', $output);
    }

    private function determineClassContext(string $originalClassName): ClassContext
    {
        $modifiersContext = $this->classUtils->splitModifiers($originalClassName);

        $classGroupId = $this->classUtils->getClassGroupId(
            is_null($modifiersContext->maybePostfixModifierPosition)
                ? $modifiersContext->baseClassName
                : substr($modifiersContext->baseClassName, 0, $modifiersContext->maybePostfixModifierPosition)
        );

        $hasPostfixModifier = is_int($modifiersContext->maybePostfixModifierPosition);

        if (!$classGroupId) {
            if (is_null($modifiersContext->maybePostfixModifierPosition)) {
                return new ClassContext(
                    isTailwindClass: false,
                    originalClassName: $originalClassName
                );
            }

            $classGroupId = $this->classUtils->getClassGroupId($modifiersContext->baseClassName);

            if (!$classGroupId) {
                return new ClassContext(
                    isTailwindClass: false,
                    originalClassName: $originalClassName
                );
            }

            $hasPostfixModifier = false;
        }

        $variantModifier = implode(':', $this->classUtils->sortModifiers($modifiersContext->modifiers));
        $modifierId = $modifiersContext->hasImportantModifier
            ? $variantModifier . self::IMPORTANT_MODIFIER
            : $variantModifier;

        return new ClassContext(
            isTailwindClass: true,
            originalClassName: $originalClassName,
            hasPostfixModifier: $hasPostfixModifier,
            modifierId: $modifierId,
            classGroupId: $classGroupId,
        );
    }

    private function filterConflictingClasses(array $classes): array
    {
        /**
         * Set of classGroupIds in following format:
         * `{importantModifier}{variantModifiers}{classGroupId}`
         *
         * @example 'float'
         * @example 'hover:focus:bg-color'
         * @example 'md:!pr'
         *
         * @var string[] $classGroupsInConflict
         */
        $classGroupsInConflict = [];

        return array_filter($classes, function (ClassContext $context) use (&$classGroupsInConflict) {
            if (!$context->isTailwindClass) {
                return true;
            }

            $classId = $context->modifierId . $context->classGroupId;
            if (in_array($classId, $classGroupsInConflict)) {
                return false;
            }

            $classGroupsInConflict[] = $classId;

            $conflicts = $this->classUtils->getConflictingClassGroupIds((string) $context->classGroupId, $context->hasPostfixModifier);
            foreach ($conflicts as $group) {
                $classGroupsInConflict[] = $context->modifierId . $group;
            }

            return true;
        });
    }
}
