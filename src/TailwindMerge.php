<?php

namespace YieldStudio\TailwindMerge;

use Closure;
use Illuminate\Support\Collection;
use YieldStudio\TailwindMerge\Interfaces\TailwindMergePlugin;

class TailwindMerge
{
    const SPLIT_CLASSES_REGEX = '/\s+/';
    const IMPORTANT_MODIFIER = '!';

    private ConfigUtils $configUtils;

    public TailwindMergeConfig $config;

    private static ?TailwindMerge $shared = null;

    public function __construct(?TailwindMergeConfig $config = null)
    {
        $this->config = $config ?? TailwindMergeConfig::default();
        $this->configUtils = new ConfigUtils($this->config);
    }

    public function merge(...$classList)
    {
        $classList = $this->join(...$classList);

        $cachedResult = $this->configUtils->cache->get($classList);
        if ($cachedResult) {
            return $cachedResult;
        }

        $result = $this->mergeClassList($classList);
        $this->configUtils->cache->set($classList, $result);

        return $result;
    }

    private function join(...$classList): string
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

    public function extend(Closure|TailwindMergePlugin ...$plugins): static
    {
        foreach ($plugins as $plugin) {
            $this->config = $plugin($this->config);
        }

        $this->configUtils = new ConfigUtils($this->config);

        return $this;
    }

    public static function shared(): static
    {
        if (!self::$shared) {
            self::$shared = new TailwindMerge();
        }

        return self::$shared;
    }

    private function mergeClassList(string $classList): string
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
                $splitedModifiers = $this->configUtils->splitModifiers($originalClassName);

                $classGroupId = $this->configUtils->getClassGroupId($splitedModifiers['baseClassName']);
                if (!$classGroupId) {
                    return [
                        'isTailwindClass' => false,
                        'originalClassName' => $originalClassName
                    ];
                }

                $variantModifier = implode(':', $this->sortModifiers($splitedModifiers['modifiers']));
                $modifierId = $splitedModifiers['hasImportantModifier']
                    ? $variantModifier . self::IMPORTANT_MODIFIER
                    : $variantModifier;

                return [
                    'isTailwindClass' => true,
                    'modifierId' => $modifierId,
                    'classGroupId' => $classGroupId,
                    'originalClassName' => $originalClassName
                ];
            })
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

                $conflicts = $this->configUtils->getConflictingClassGroupIds($parsed['classGroupId']);
                foreach ($conflicts as $group) {
                    $classGroupsInConflict->push($parsed['modifierId'] . $group);
                }

                return true;
            })
            ->reverse()
            ->map(fn(array $parsed) => $parsed['originalClassName'])
            ->join(' ');
    }

    private function sortModifiers(array $modifiers): array
    {
        if (count($modifiers) <= 1) {
            return $modifiers;
        }

        $sortedModifiers = [];
        $unsortedModifiers = [];

        foreach ($modifiers as $modifier) {
            $isArbitraryVariant = $modifier[0] === '[';
            if ($isArbitraryVariant) {
                sort($unsortedModifiers);
                array_push($sortedModifiers, ...$unsortedModifiers);
                $sortedModifiers[] = $modifier;
                $unsortedModifiers = [];
            } else {
                $unsortedModifiers[] = $modifier;
            }
        }

        sort($unsortedModifiers);
        array_push($sortedModifiers, ...$unsortedModifiers);

        return $sortedModifiers;
    }
}