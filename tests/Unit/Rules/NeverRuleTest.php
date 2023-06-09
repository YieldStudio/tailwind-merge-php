<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\NeverRule;

test('never rule', function () {
    expect(new NeverRule())
        ->execute('')->toBeFalse()
        ->execute('[]')->toBeFalse()
        ->execute('foo')->toBeFalse();
});
