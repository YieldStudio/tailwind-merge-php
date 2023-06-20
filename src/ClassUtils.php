<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge;

final class ClassUtils
{
    protected const IMPORTANT_MODIFIER = '!';

    protected const ARBITRARY_PROPERTY_REGEX = '/^\[(.+)]$/';

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

    public function getConflictingClassGroupIds(string $classGroupId, bool $hasPostfixModifier): array
    {
        $conflicts = $this->config->conflictingClassGroups[$classGroupId] ?? [];

        if ($hasPostfixModifier && array_key_exists($classGroupId, $this->config->conflictingClassGroupModifiers)) {
            return [...$conflicts, ...$this->config->conflictingClassGroupModifiers[$classGroupId]];
        }

        return $conflicts;
    }

    /*
    * @see https://github.com/tailwindlabs/tailwindcss/blob/v3.2.2/src/util/splitAtTopLevelOnly.js
    */
    public function splitModifiers(string $className): ClassModifiersContext
    {
        $separator = $this->config->separator;
        $separatorLength = strlen($separator);
        $isSeparatorSingleCharacter = $separatorLength === 1;
        $firstSeparatorCharacter = $separator[0];

        $modifiers = [];

        $bracketDepth = 0;
        $modifierStart = 0;
        $postfixModifierPosition = null;

        for ($index = 0; $index < strlen($className); $index++) {
            $currentCharacter = $className[$index];

            if ($bracketDepth === 0) {
                if (
                    $currentCharacter === $firstSeparatorCharacter &&
                    ($isSeparatorSingleCharacter || substr($className, $index, $separatorLength) === $separator)
                ) {
                    $modifiers[] = substr($className, $modifierStart, $index - $modifierStart);
                    $modifierStart = $index + $separatorLength;

                    continue;
                }

                if ($currentCharacter === '/') {
                    $postfixModifierPosition = $index;

                    continue;
                }
            }

            if ($currentCharacter === '[') {
                $bracketDepth++;
            } elseif ($currentCharacter === ']') {
                $bracketDepth--;
            }
        }

        $baseClassNameWithImportantModifier = count($modifiers) === 0 ? $className : substr($className, $modifierStart);
        $hasImportantModifier = str_starts_with($baseClassNameWithImportantModifier, self::IMPORTANT_MODIFIER);
        $baseClassName = $hasImportantModifier
            ? substr($baseClassNameWithImportantModifier, 1)
            : $baseClassNameWithImportantModifier;

        $maybePostfixModifierPosition = $postfixModifierPosition && $postfixModifierPosition > $modifierStart
            ? $postfixModifierPosition - $modifierStart
            : null;

        return new ClassModifiersContext($modifiers, $hasImportantModifier, $baseClassName, $maybePostfixModifierPosition);
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
        $nextClassPartObject = $classPart->nextPart[$currentClassPart] ?? null;
        $classGroupFromNextClassPart = $nextClassPartObject ? $this->getGroupRecursive(array_slice($classParts, 1), $nextClassPartObject) : null;

        if ($classGroupFromNextClassPart) {
            return $classGroupFromNextClassPart;
        }

        if (empty($classPart->validators)) {
            return null;
        }

        $classRest = implode('-', $classParts);

        foreach ($classPart->validators as $classValidator) {
            if ($classValidator->rule->execute($classRest)) {
                return $classValidator->classGroupId;
            }
        }

        return null;
    }

    protected function getGroupIdForArbitraryProperty(string $className): ?string
    {
        $matches = [];
        if (preg_match(self::ARBITRARY_PROPERTY_REGEX, $className, $matches)) {
            $arbitraryPropertyClassName = $matches[1];
            if ($arbitraryPropertyClassName) {
                $property = substr($arbitraryPropertyClassName, 0, (int) strpos($arbitraryPropertyClassName, ':'));
                // I use two dots here because one dot is used as prefix for class groups in plugins
                return 'arbitrary..'.$property;
            }
        }

        return null;
    }
}
