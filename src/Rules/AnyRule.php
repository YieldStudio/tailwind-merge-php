<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

final class AnyRule implements RuleInterface
{
    public function execute(string $value): bool
    {
        return true;
    }
}
