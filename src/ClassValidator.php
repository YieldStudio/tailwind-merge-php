<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

final class ClassValidator
{
    public function __construct(
        public readonly string $classGroupId,
        public readonly RuleInterface $rule
    ) {
    }
}
