<?php

use YieldStudio\TailwindMerge\Rules\ArbitraryNumberRule;

test('arbitrary number rule', function () {
    expect(new ArbitraryNumberRule)
        ->execute('[number:1]')->toBeTrue()
        ->execute('[1]')->toBeTrue()
        ->execute('[123453]')->toBeTrue()
        ->execute('[1.123]')->toBeTrue()
        ->execute('1')->toBeFalse()
        ->execute('1]')->toBeFalse()
        ->execute('[1')->toBeFalse()
        ->execute('[foo]')->toBeFalse();
});