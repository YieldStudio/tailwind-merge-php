<?php

namespace YieldStudio\TailwindMerge;

use YieldStudio\TailwindMerge\Interfaces\ValidatorInterface;

class ClassValidatorObject
{
    public function __construct(
        public readonly string $classGroupId,
        public readonly ValidatorInterface $validator
    )
    {
    }
}