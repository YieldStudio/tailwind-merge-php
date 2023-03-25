<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\ValidatorInterface;

class LengthRule implements ValidatorInterface
{
    protected const FRACTION_REGEX = '/^\d+\/\d+$/';
    protected const STRING_LENGTHS = ['px', 'full', 'screen'];

    public function execute(string $value): bool
    {
        return is_numeric($value)
            || in_array($value, self::STRING_LENGTHS)
            || !!preg_match(self::FRACTION_REGEX, $value)
            || (new ArbitraryLengthRule)->execute($value);
    }
}