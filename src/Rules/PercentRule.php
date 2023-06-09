<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

final class PercentRule implements RuleInterface
{
    public function execute(string $value): bool
    {
        $lastChar = $value[strlen($value) - 1];
        if ($lastChar !== '%'){
            return false;
        }

        $numeric = rtrim($value, '%');
        return is_numeric($numeric);
    }
}
