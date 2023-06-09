<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\NumberRule;

test('number rule', function () {
    expect(new NumberRule())
        ->execute('[number:1]')->toBeFalse()
        ->execute('[1]')->toBeFalse()
        ->execute('[123453]')->toBeFalse()
        ->execute('[1.123]')->toBeFalse()
        ->execute('1')->toBeTrue()
        ->execute('1.123')->toBeTrue()
        ->execute('1]')->toBeFalse()
        ->execute('[1')->toBeFalse()
        ->execute('[foo]')->toBeFalse();
});
