<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\ArbitraryIntegerRule;

test('arbitrary integer rule', function () {
    expect(new ArbitraryIntegerRule())
        ->execute('[number:1]')->toBeTrue()
        ->execute('[1]')->toBeTrue()
        ->execute('[123453]')->toBeTrue()
        ->execute('1')->toBeFalse()
        ->execute('1]')->toBeFalse()
        ->execute('[1')->toBeFalse()
        ->execute('[foo]')->toBeFalse()
        ->execute('[1.123]')->toBeFalse();
});
