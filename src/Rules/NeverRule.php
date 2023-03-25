<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\ValidatorInterface;

class NeverRule implements ValidatorInterface
{

    public function execute(string $value): bool
    {
        return false;
    }
}