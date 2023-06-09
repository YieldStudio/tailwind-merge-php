<?php

namespace YieldStudio\TailwindMerge\Rules;

class ArbitraryLengthRule extends ArbitraryValueRule
{
    protected const LENGTH_UNIT_REGEX = '/\d+(%|px|r?em|[sdl]?v([hwib]|min|max)|pt|pc|in|cm|mm|cap|ch|ex|r?lh|cq(w|h|i|b|min|max))|^0$/';

    protected ?string $parameter = 'length';

    protected function testValue(string $value): bool
    {
        return !!preg_match(self::LENGTH_UNIT_REGEX, $value);
    }
}
