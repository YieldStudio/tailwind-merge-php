<?php

namespace YieldStudio\TailwindMerge;

class ClassUtils
{

    protected const IMPORTANT_MODIFIER = '!';

    protected const ARBITRARY_PROPERTY_REGEX = '/^\[(.+)\]$/';

    protected readonly ClassPart $classMap;

    public function __construct(public readonly TailwindMergeConfig $config)
    {
        $this->classMap = ClassMapFactory::create($config);
    }

    public function getClassGroupId(string $className): ?string
    {
        $classParts = explode('-', $className);

        // Classes like `-inset-1` produce an empty string as first classPart.
        // We assume that classes for negative values are used correctly and remove it from classParts.
        if ($classParts[0] === '' && count($classParts) !== 1) {
            array_shift($classParts);
        }

        return $this->getGroupRecursive($classParts, $this->classMap) ?? $this->getGroupIdForArbitraryProperty($className);
    }

    public function getConflictingClassGroupIds(string $classGroupId)
    {
        return $this->config->conflictingClassGroups[$classGroupId] ?? [];
    }

    /*
    * Inspired by https://github.com/tailwindlabs/tailwindcss/blob/v3.2.2/src/util/splitAtTopLevelOnly.js
    */
    public function splitModifiers($className): ClassModifiersContext
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

        return new ClassModifiersContext($modifiers, $hasImportantModifier, $baseClassName);
    }

    /**
     * Sorts modifiers according to following schema:
     * - Predefined modifiers are sorted alphabetically
     * - When an arbitrary variant appears, it must be preserved which modifiers are before and after it
     */
    public function sortModifiers(array $modifiers): array
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

    protected function getGroupRecursive(array $classParts, ClassPart $classPart): ?string
    {
        if (count($classParts) === 0) {
            return $classPart->classGroupId;
        }


        $currentClassPart = $classParts[0];
        $nextClassPartObject = $classPart->nextPart->get($currentClassPart);
        $classGroupFromNextClassPart = $nextClassPartObject ? $this->getGroupRecursive(array_slice($classParts, 1), $nextClassPartObject) : null;

        if ($classGroupFromNextClassPart) {
            return $classGroupFromNextClassPart;
        }

        if ($classPart->validators->isEmpty()) {
            return null;
        }

        $classRest = implode('-', $classParts);
        return $classPart
            ->validators
            ->first(fn(ClassValidator $classValidator) => $classValidator->rule->execute($classRest))
            ?->classGroupId ?? null;
    }

    protected function getGroupIdForArbitraryProperty(string $className): ?string
    {
        $matches = [];
        if (preg_match(self::ARBITRARY_PROPERTY_REGEX, $className, $matches)) {
            $arbitraryPropertyClassName = $matches[1];
            if ($arbitraryPropertyClassName) {
                $property = substr($arbitraryPropertyClassName, 0, strpos($arbitraryPropertyClassName, ':'));
                // I use two dots here because one dot is used as prefix for class groups in plugins
                return 'arbitrary..' . $property;
            }
        }

        return null;
    }

}