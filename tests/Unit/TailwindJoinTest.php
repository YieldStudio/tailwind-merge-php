<?php

use YieldStudio\TailwindMerge\TailwindMerge;

test('conditionnal classes', function () {
    $twMerge = new TailwindMerge();
    expect($twMerge->merge('foo', [
        'bar' => true,
        'noop' => false
    ], 'test', [
        'test2' => true,
        'noop' => false
    ]))->toBe('foo bar test test2');
});
