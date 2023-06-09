<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\ArbitraryPositionRule;

test('arbitrary position rule', function () {
    expect(new ArbitraryPositionRule())
        ->execute('[position:2px]')->toBeTrue()
        ->execute('[position:bla]')->toBeTrue()
        ->execute('[2px]')->toBeFalse()
        ->execute('[bla]')->toBeFalse()
        ->execute('position:2px')->toBeFalse();
});
