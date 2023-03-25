<?php

use YieldStudio\TailwindMerge\Rules\NumberRule;

test('number rule', function () {
    expect(new NumberRule)
        ->execute('[number:1]')->toBeTrue()
        ->execute('[1]')->toBeTrue()
        ->execute('[123453]')->toBeTrue()
        ->execute('[1.123]')->toBeTrue()
        ->execute('1')->toBeTrue()
        ->execute('1]')->toBeFalse()
        ->execute('[1')->toBeFalse()
        ->execute('[foo]')->toBeFalse();
});