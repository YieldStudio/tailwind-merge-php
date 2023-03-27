<?php

namespace YieldStudio\TailwindMerge;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

class ClassValidator
{
    public function __construct(
        public readonly string $classGroupId,
        public readonly RuleInterface $rule
    )
    {
    }
}