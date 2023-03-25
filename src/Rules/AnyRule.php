<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\ValidatorInterface;

class AnyRule implements ValidatorInterface
{

    public function execute(string $value): bool
    {
        return true;
    }
}