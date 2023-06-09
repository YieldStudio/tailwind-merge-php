<?php

namespace YieldStudio\TailwindMerge\Rules;

use YieldStudio\TailwindMerge\Interfaces\RuleInterface;

class ArbitraryValueRule implements RuleInterface
{
    protected const REGEX = '/^\[(?:([a-z-]+):)?(.+)]$/i';

    protected ?string $parameter = null;

    public function execute(string $value): bool
    {
        $matches = [];

        if (preg_match(self::REGEX, $value, $matches)) {
            if (!empty($matches[1])) {
                return $matches[1] === $this->parameter;
            }

            return $this->testValue($matches[2]);
        }

        return false;
    }

    protected function testValue(string $value): bool
    {
        return true;
    }
}
