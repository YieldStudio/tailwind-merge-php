<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

final class LengthRule implements RuleInterface
{
    protected const FRACTION_REGEX = '/^\d+\/\d+$/';

    protected const STRING_LENGTHS = ['px', 'full', 'screen'];

    public function execute(string $value): bool
    {
        return is_numeric($value)
            || in_array($value, self::STRING_LENGTHS)
            || (bool) preg_match(self::FRACTION_REGEX, $value);
    }
}
