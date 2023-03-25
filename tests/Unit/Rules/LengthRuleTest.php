<?php

use YieldStudio\TailwindMerge\Rules\LengthRule;

test('length rule', function () {
    expect(new LengthRule)
        ->execute('1')->toBeTrue()
        ->execute('1023713')->toBeTrue()
        ->execute('1.5')->toBeTrue()
        ->execute('1231.503761')->toBeTrue()
        ->execute('px')->toBeTrue()
        ->execute('full')->toBeTrue()
        ->execute('screen')->toBeTrue()
        ->execute('1/2')->toBeTrue()
        ->execute('123/345')->toBeTrue()
        ->execute('[3.7%]')->toBeTrue()
        ->execute('[481px]')->toBeTrue()
        ->execute('[19.1rem]')->toBeTrue()
        ->execute('[50vw]')->toBeTrue()
        ->execute('[56vh]')->toBeTrue()
        ->execute('[length:var(--arbitrary)]')->toBeTrue()
        ->execute('1d5')->toBeFalse()
        ->execute('[1]')->toBeFalse()
        ->execute('[12px')->toBeFalse()
        ->execute('12px]')->toBeFalse()
        ->execute('one')->toBeFalse();
});