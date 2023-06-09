<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\PercentRule;

test('percent rule', function () {
    expect(new PercentRule)
        ->execute('1%')->toBeTrue()
        ->execute('100.001%')->toBeTrue()
        ->execute('.01%')->toBeTrue()
        ->execute('0%')->toBeTrue()
        ->execute('0')->toBeFalse()
        ->execute('one%')->toBeFalse();
});
