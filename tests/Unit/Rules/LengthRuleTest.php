<?php

declare(strict_types=1);

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
        ->execute('[3.7%]')->toBeFalse()
        ->execute('[481px]')->toBeFalse()
        ->execute('[19.1rem]')->toBeFalse()
        ->execute('[50vw]')->toBeFalse()
        ->execute('[56vh]')->toBeFalse()
        ->execute('[length:var(--arbitrary)]')->toBeFalse()
        ->execute('1d5')->toBeFalse()
        ->execute('[1]')->toBeFalse()
        ->execute('[12px')->toBeFalse()
        ->execute('12px]')->toBeFalse()
        ->execute('one')->toBeFalse();
});
