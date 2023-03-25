<?php

namespace YieldStudio\TailwindMerge;

use YieldStudio\TailwindMerge\Interfaces\ValidatorInterface;

abstract class ClassMapFactory
{

    const CLASS_PART_SEPARATOR = '-';

    public static function create(TailwindMergeConfig $config): ClassPartObject
    {
        $classMap = new ClassPartObject();

        $prefixedClassGroups = self::getPrefixedClassGroups(
            $config->classGroups,
            $config->prefix ?? null
        );

        foreach ($prefixedClassGroups as $classGroupId => $classGroup) {
            self::processClassesRecursively($classGroup, $classMap, $classGroupId, $config->theme);
        }

        return $classMap;
    }

    public static function processClassesRecursively(array $classGroup, ClassPartObject $classPartObject, string $classGroupId, array $theme): void
    {
        foreach ($classGroup as $classDefinition) {
            if (is_string($classDefinition)) {
                $classPartObjectToEdit = $classDefinition === '' ? $classPartObject : self::getPart($classPartObject, $classDefinition);
                $classPartObjectToEdit->setClassGroupId($classGroupId);
                continue;
            }

            if ($classDefinition instanceof ThemeGetter || $classDefinition instanceof ValidatorInterface) {
                if ($classDefinition instanceof ThemeGetter) {
                    self::processClassesRecursively(
                        $classDefinition->execute($theme),
                        $classPartObject,
                        $classGroupId,
                        $theme
                    );

                    continue;
                }

                $classPartObject->validators->push(new ClassValidatorObject(
                    $classGroupId,
                    $classDefinition
                ));

                continue;
            }

            foreach ($classDefinition as $key => $classGroup) {
                self::processClassesRecursively(
                    $classGroup,
                    self::getPart($classPartObject, $key),
                    $classGroupId,
                    $theme
                );
            }
        }
    }

    public static function getPart(ClassPartObject $classPartObject, string $path): ClassPartObject {
        $currentClassPartObject = $classPartObject;

        foreach(explode(self::CLASS_PART_SEPARATOR, $path) as $pathPart){
            if (!$currentClassPartObject->nextPart->has($pathPart)) {
                $currentClassPartObject->nextPart->put($pathPart, new ClassPartObject());
            }

            $currentClassPartObject = $currentClassPartObject->nextPart->get($pathPart);
        }

        return $currentClassPartObject;
    }

    public static function getPrefixedClassGroups(array $classGroups, ?string $prefix): array
    {
        if (!$prefix) {
            return $classGroups;
        }

        $output = [];
        foreach ($classGroups as $classGroupId => $classGroup) {
            $output[$classGroupId] = array_map(function ($classDefinition) use ($prefix) {
                if (is_string($classDefinition)) {
                    return $prefix . $classDefinition;
                }

                if (is_array($classDefinition)) {
                    $prefixedClassDefinition = [];
                    foreach ($classDefinition as $key => $value) {
                        $prefixedClassDefinition[$prefix . $key] = $value;
                    }

                    return $prefixedClassDefinition;
                }

                return $classDefinition;
            }, $classGroup);
        }

        return $output;
    }
}