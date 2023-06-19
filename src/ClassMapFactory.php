<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

abstract class ClassMapFactory
{
    protected const CLASS_PART_SEPARATOR = '-';

    public static function create(TailwindMergeConfig $config): ClassPart
    {
        $classMap = new ClassPart();

        $prefixedClassGroups = self::getPrefixedClassGroups(
            $config->classGroups,
            $config->prefix ?? null
        );

        foreach ($prefixedClassGroups as $classGroupId => $classGroup) {
            self::processClassesRecursively($classGroup, $classMap, $classGroupId, $config->theme);
        }

        return $classMap;
    }

    protected static function processClassesRecursively(array $classGroup, ClassPart $classPart, string $classGroupId, array $theme): void
    {
        foreach ($classGroup as $classDefinition) {
            if (is_string($classDefinition)) {
                $classPartToEdit = $classDefinition === '' ? $classPart : self::getPart($classPart, $classDefinition);
                $classPartToEdit->setClassGroupId($classGroupId);

                continue;
            }

            if ($classDefinition instanceof ThemeGetter || $classDefinition instanceof RuleInterface) {
                if ($classDefinition instanceof ThemeGetter) {
                    self::processClassesRecursively(
                        $classDefinition->execute($theme),
                        $classPart,
                        $classGroupId,
                        $theme
                    );

                    continue;
                }

                $classPart->validators[] = new ClassValidator(
                    $classGroupId,
                    $classDefinition
                );

                continue;
            }

            foreach ($classDefinition as $key => $classGroup) {
                self::processClassesRecursively(
                    $classGroup,
                    self::getPart($classPart, $key),
                    $classGroupId,
                    $theme
                );
            }
        }
    }

    protected static function getPart(ClassPart $classPart, string $path): ClassPart
    {
        $currentClassPartObject = $classPart;

        foreach (explode(self::CLASS_PART_SEPARATOR, $path) as $pathPart) {
            if (! array_key_exists($pathPart, $currentClassPartObject->nextPart)) {
                $currentClassPartObject->nextPart[$pathPart] = new ClassPart();
            }

            $currentClassPartObject = $currentClassPartObject->nextPart[$pathPart];
        }

        return $currentClassPartObject;
    }

    protected static function getPrefixedClassGroups(array $classGroups, ?string $prefix): array
    {
        if (! $prefix) {
            return $classGroups;
        }

        $output = [];
        foreach ($classGroups as $classGroupId => $classGroup) {
            $output[$classGroupId] = array_map(function ($classDefinition) use ($prefix) {
                if (is_string($classDefinition)) {
                    return $prefix.$classDefinition;
                }

                if (is_array($classDefinition)) {
                    $prefixedClassDefinition = [];
                    foreach ($classDefinition as $key => $value) {
                        $prefixedClassDefinition[$prefix.$key] = $value;
                    }

                    return $prefixedClassDefinition;
                }

                return $classDefinition;
            }, $classGroup);
        }

        return $output;
    }
}
