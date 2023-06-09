<?php

use YieldStudio\TailwindMerge\Rules\ArbitraryLengthRule;

test('arbitrary length rule', function () {
    expect(new ArbitraryLengthRule)
        ->execute('[0]')->toBeTrue()
        ->execute('[3.7%]')->toBeTrue()
        ->execute('[481px]')->toBeTrue()
        ->execute('[19.1rem]')->toBeTrue()
        ->execute('[50vw]')->toBeTrue()
        ->execute('[56vh]')->toBeTrue()
        ->execute('[length:var(--arbitrary)]')->toBeTrue()
        ->execute('1')->toBeFalse()
        ->execute('3px')->toBeFalse()
        ->execute('1d5')->toBeFalse()
        ->execute('[1]')->toBeFalse()
        ->execute('[12px')->toBeFalse()
        ->execute('12px]')->toBeFalse()
        ->execute('one')->toBeFalse();
});
