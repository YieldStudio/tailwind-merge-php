<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\ArbitraryShadowRule;

test('arbitrary shadow rule', function () {
    expect(new ArbitraryShadowRule)
        ->execute('[0_35px_60px_-15px_rgba(0,0,0,0.3)]')->toBeTrue()
        ->execute('[0_0_#00f]')->toBeTrue()
        ->execute('[.5rem_0_rgba(5,5,5,5)]')->toBeTrue()
        ->execute('[-.5rem_0_#123456]')->toBeTrue()
        ->execute('[0.5rem_-0_#123456]')->toBeTrue()
        ->execute('[0.5rem_-0.005vh_#123456]')->toBeTrue()
        ->execute('[0.5rem_-0.005vh]')->toBeTrue()
        ->execute('[rgba(5,5,5,5)]')->toBeFalse()
        ->execute('[#00f]')->toBeFalse()
        ->execute('[something-else]')->toBeFalse();
});
