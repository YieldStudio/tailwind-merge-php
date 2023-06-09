<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\IntegerRule;

test('integer rule', function () {
    expect(new IntegerRule())
        ->execute('1')->toBeTrue()
        ->execute('123')->toBeTrue()
        ->execute('8312')->toBeTrue()
        ->execute('[8312]')->toBeFalse()
        ->execute('[2]')->toBeFalse()
        ->execute('[8312px]')->toBeFalse()
        ->execute('[8312%]')->toBeFalse()
        ->execute('[8312rem]')->toBeFalse()
        ->execute('8312.2')->toBeFalse()
        ->execute('1.2')->toBeFalse()
        ->execute('one')->toBeFalse()
        ->execute('1/2')->toBeFalse()
        ->execute('1%')->toBeFalse()
        ->execute('1px')->toBeFalse();
});
