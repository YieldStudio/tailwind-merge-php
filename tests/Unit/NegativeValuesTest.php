<?php

it('handles negative value conflicts correctly')->twMerge([
    '-m-2 -m-5' => '-m-5',
    '-top-12 -top-2000' => '-top-2000',
]);

it('handles conflicts between positive and negative values correctly')->twMerge([
    '-m-2 m-auto' => 'm-auto',
    'top-12 -top-69' => '-top-69'
]);

it('handles conflicts across groups with negative values correctly')->twMerge([
    '-right-1 inset-x-1' => 'inset-x-1',
    'hover:focus:-right-1 focus:hover:inset-x-1' => 'focus:hover:inset-x-1',
]);
