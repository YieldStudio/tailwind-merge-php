<?php

namespace YieldStudio\TailwindMerge;

class ConfigUtils
{

    public readonly LruCache $cache;
    public readonly ClassUtils $classUtils;

    const IMPORTANT_MODIFIER = '!';

    public function __construct(public readonly TailwindMergeConfig $config){
        $this->cache = new LruCache($config->cacheSize);
        $this->classUtils = new ClassUtils($config);
    }

    public function getClassGroupId(string $className): ?string {
        return $this->classUtils->getClassGroupId($className);
    }

    public function getConflictingClassGroupIds(string $classGroupId) {
        return $this->classUtils->getConflictingClassGroupIds($classGroupId);
    }

    public function splitModifiers($className): array
    {
        $bracketDepth = 0;
        $modifiers = [];
        $modifierStart = 0;

        for ($index = 0; $index < strlen($className); $index++) {
            $char = $className[$index];

            if ($bracketDepth === 0 && $char === $this->config->separator[0]) {
                if (
                    strlen($this->config->separator) === 1 ||
                    substr($className, $index, strlen($this->config->separator)) === $this->config->separator
                ) {
                    $modifiers[] = substr($className, $modifierStart, $index - $modifierStart);
                    $modifierStart = $index + strlen($this->config->separator);
                }
            }

            if ($char === '[') {
                $bracketDepth++;
            } else if ($char === ']') {
                $bracketDepth--;
            }
        }

        $baseClassNameWithImportantModifier = count($modifiers) === 0 ? $className : substr($className, $modifierStart);
        $hasImportantModifier = str_starts_with($baseClassNameWithImportantModifier, self::IMPORTANT_MODIFIER);
        $baseClassName = $hasImportantModifier
            ? substr($baseClassNameWithImportantModifier, 1)
            : $baseClassNameWithImportantModifier;

        // TODO obj
        return [
            'modifiers' => $modifiers,
            'hasImportantModifier' => $hasImportantModifier,
            'baseClassName' => $baseClassName,
        ];
    }

}