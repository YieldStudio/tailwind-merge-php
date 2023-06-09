<?php

namespace YieldStudio\TailwindMerge;

use Closure;
use Illuminate\Support\Collection;
use YieldStudio\TailwindMerge\Interfaces\TailwindMergePlugin;

class TailwindMerge
{
    protected const SPLIT_CLASSES_REGEX = '/\s+/';

    protected const IMPORTANT_MODIFIER = '!';

    protected LruCache $cache;

    protected ClassUtils $classUtils;

    protected TailwindMergeConfig $config;

    protected static ?TailwindMerge $instance = null;

    public function __construct(?TailwindMergeConfig $config = null)
    {
        $this->config = $config ?? TailwindMergeConfig::default();
        $this->classUtils = new ClassUtils($this->config);

        $this->cache = new LruCache($this->config->cacheSize);
    }

    public function merge(...$classList)
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

    public function extend(Closure|TailwindMergePlugin ...$plugins): static
    {
        foreach ($plugins as $plugin) {
            $this->config = $plugin($this->config);
        }

        $this->classUtils = new ClassUtils($this->config);
        $this->cache = new LruCache($this->config->cacheSize);

        return $this;
    }

    public static function instance(): static
    {
        if (!self::$instance) {
            self::$instance = new TailwindMerge();
        }

        return self::$instance;
    }

    protected function mergeClassList(string $classList): string
    {
        /**
         * Set of classGroupIds in following format:
         * `{importantModifier}{variantModifiers}{classGroupId}`
         * @example 'float'
         * @example 'hover:focus:bg-color'
         * @example 'md:!pr'
         */
        $classGroupsInConflict = new Collection();

        return (new Collection(preg_split(self::SPLIT_CLASSES_REGEX, trim($classList))))
            ->map(function ($originalClassName) {
                $modifiersContext = $this->classUtils->splitModifiers($originalClassName);

                $classGroupId = $this->classUtils->getClassGroupId(
                    is_null($modifiersContext->maybePostfixModifierPosition)
                        ? $modifiersContext->baseClassName
                        : substr($modifiersContext->baseClassName, 0, $modifiersContext->maybePostfixModifierPosition)
                );

                $hasPostfixModifier = is_int($modifiersContext->maybePostfixModifierPosition);

                if (!$classGroupId) {
                    if (is_null($modifiersContext->maybePostfixModifierPosition)) {
                        return [
                            'isTailwindClass' => false,
                            'originalClassName' => $originalClassName
                        ];
                    }

                    $classGroupId = $this->classUtils->getClassGroupId($modifiersContext->baseClassName);

                    if (!$classGroupId) {
                        return [
                            'isTailwindClass' => false,
                            'originalClassName' => $originalClassName
                        ];
                    }

                    $hasPostfixModifier = false;
                }

                $variantModifier = implode(':', $this->classUtils->sortModifiers($modifiersContext->modifiers));
                $modifierId = $modifiersContext->hasImportantModifier
                    ? $variantModifier . self::IMPORTANT_MODIFIER
                    : $variantModifier;

                return [
                    'isTailwindClass' => true,
                    'modifierId' => $modifierId,
                    'classGroupId' => $classGroupId,
                    'originalClassName' => $originalClassName,
                    'hasPostfixModifier' => $hasPostfixModifier
                ];
            })
            // Last class in conflict wins, so we need to filter conflicting classes in reverse order.
            ->reverse()
            ->filter(function (array $parsed) use ($classGroupsInConflict) {
                if (!$parsed['isTailwindClass']) {
                    return true;
                }

                $classId = $parsed['modifierId'] . $parsed['classGroupId'];
                if ($classGroupsInConflict->contains($classId)) {
                    return false;
                }

                $classGroupsInConflict->push($classId);

                $conflicts = $this->classUtils->getConflictingClassGroupIds($parsed['classGroupId'], $parsed['hasPostfixModifier']);
                foreach ($conflicts as $group) {
                    $classGroupsInConflict->push($parsed['modifierId'] . $group);
                }

                return true;
            })
            ->reverse()
            ->map(fn(array $parsed) => $parsed['originalClassName'])
            ->join(' ');
    }

    protected function join(...$classList): string
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
}
