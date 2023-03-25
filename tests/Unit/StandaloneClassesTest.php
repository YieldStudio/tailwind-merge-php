<?php

test('merges standalone classes from same group correctly')->twMerge([
    'inline block' => 'block',
    'hover:block hover:inline' => 'hover:inline',
    'hover:block hover:block' => 'hover:block',
    'inline hover:inline focus:inline hover:block hover:focus:block' => 'inline focus:inline hover:block hover:focus:block',
    'underline line-through' => 'line-through',
    'line-through no-underline' => 'no-underline',
]);
