<?php

use YieldStudio\TailwindMerge\Rules\ArbitraryValueRule;

test('arbitrary value rule', function () {
    expect(new ArbitraryValueRule)
        ->execute('[1]')->toBeTrue()
        ->execute('[bla]')->toBeTrue()
        ->execute('[not-an-arbitrary-value?]')->toBeTrue()
        ->execute('[auto,auto,minmax(0,1fr),calc(100vw-50%)]')->toBeTrue()
        ->execute('[]')->toBeFalse()
        ->execute('[1')->toBeFalse()
        ->execute('1]')->toBeFalse()
        ->execute('1')->toBeFalse()
        ->execute('one')->toBeFalse()
        ->execute('o[n]e')->toBeFalse();
});