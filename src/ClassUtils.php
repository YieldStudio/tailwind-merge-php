<?php

namespace YieldStudio\TailwindMerge;

class ClassUtils
{

    const ARBITRARY_PROPERTY_REGEX = '/^\[(.+)\]$/';

    private readonly ClassPartObject $classMap;

    public function __construct(public readonly TailwindMergeConfig $config)
    {
        $this->classMap = ClassMapFactory::create($config);
    }

    public function getClassGroupId(string $className): ?string
    {
        $classParts = explode('-', $className);

        if ($classParts[0] === '' && count($classParts) !== 1) {
            array_shift($classParts);
        }

        return $this->getGroupRecursive($classParts, $this->classMap) ?? $this->getGroupIdForArbitraryProperty($className);
    }

    public function getConflictingClassGroupIds(string $classGroupId) {
        return $this->config->conflictingClassGroups[$classGroupId] ?? [];
    }

    private function getGroupRecursive(array $classParts, ClassPartObject $classPartObject): ?string {
        if (count($classParts) === 0) {
            return $classPartObject->classGroupId;
        }


        $currentClassPart = $classParts[0];
        $nextClassPartObject = $classPartObject->nextPart->get($currentClassPart);
        $classGroupFromNextClassPart = $nextClassPartObject ? $this->getGroupRecursive(array_slice($classParts, 1), $nextClassPartObject) : null;

        if ($classGroupFromNextClassPart) {
            return $classGroupFromNextClassPart;
        }

        if ($classPartObject->validators->isEmpty()) {
            return null;
        }

        $classRest = implode('-', $classParts);
        return $classPartObject
            ->validators
            ->first(fn(ClassValidatorObject $classValidatorObject) => $classValidatorObject->validator->execute($classRest))
            ?->classGroupId ?? null;
    }
    
    private function getGroupIdForArbitraryProperty(string $className): ?string {
        $matches = [];
        if (preg_match(self::ARBITRARY_PROPERTY_REGEX, $className, $matches)) {
            $arbitraryPropertyClassName = $matches[1];
            if ($arbitraryPropertyClassName){
                $property = substr($arbitraryPropertyClassName, 0, strpos($arbitraryPropertyClassName, ':'));
                // I use two dots here because one dot is used as prefix for class groups in plugins
                return 'arbitrary..' . $property;
            }
        }

        return null;
    }

}