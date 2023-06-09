<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

final class TshirtSizeRule implements RuleInterface
{
    protected const REGEX = '/^(\d+(\.\d+)?)?(xs|sm|md|lg|xl)$/';

    public function execute(string $value): bool
    {
        return !!preg_match(self::REGEX, $value);
    }
}
