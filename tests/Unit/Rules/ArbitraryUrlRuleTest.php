<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Rules\ArbitraryUrlRule;

test('arbitrary url rule', function () {
    expect(new ArbitraryUrlRule)
        ->execute('[url:var(--my-url)]')->toBeTrue()
        ->execute('[url(something)]')->toBeTrue()
        ->execute('[url:bla]')->toBeTrue()
        ->execute('[var(--my-url)]')->toBeFalse()
        ->execute('[bla]')->toBeFalse()
        ->execute('url:2px')->toBeFalse()
        ->execute('url(2px)')->toBeFalse();
});
