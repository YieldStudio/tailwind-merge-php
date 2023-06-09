<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\TshirtSizeRule;

test('tshirt size rule', function () {
    expect(new TshirtSizeRule())
        ->execute('xs')->toBeTrue()
        ->execute('sm')->toBeTrue()
        ->execute('md')->toBeTrue()
        ->execute('lg')->toBeTrue()
        ->execute('xl')->toBeTrue()
        ->execute('2xl')->toBeTrue()
        ->execute('2.5xl')->toBeTrue()
        ->execute('10xl')->toBeTrue()
        ->execute('2xs')->toBeTrue()
        ->execute('2lg')->toBeTrue()
        ->execute('')->toBeFalse()
        ->execute('hello')->toBeFalse()
        ->execute('1')->toBeFalse()
        ->execute('xl3')->toBeFalse()
        ->execute('2xl3')->toBeFalse()
        ->execute('-xl')->toBeFalse()
        ->execute('[sm]')->toBeFalse();
});
