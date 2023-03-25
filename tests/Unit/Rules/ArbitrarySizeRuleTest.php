<?php

use YieldStudio\TailwindMerge\Rules\ArbitrarySizeRule;

test('arbitrary size rule', function () {
    expect(new ArbitrarySizeRule)
        ->execute('[size:2px]')->toBeTrue()
        ->execute('[size:bla]')->toBeTrue()
        ->execute('[2px]')->toBeFalse()
        ->execute('[bla]')->toBeFalse()
        ->execute('size:2px')->toBeFalse();
});