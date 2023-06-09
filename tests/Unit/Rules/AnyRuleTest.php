<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\AnyRule;

test('any rule', function () {
    expect(new AnyRule())
        ->execute('')->toBeTrue()
        ->execute('[]')->toBeTrue()
        ->execute('foo')->toBeTrue();
});
