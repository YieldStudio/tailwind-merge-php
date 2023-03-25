<?php

it('conflicts across modifiers')->twMerge([
    'hover:block hover:inline' => 'hover:inline',
    'hover:block hover:focus:inline' => 'hover:block hover:focus:inline',
    'hover:block hover:focus:inline focus:hover:inline' => 'hover:block focus:hover:inline',
    'focus-within:inline focus-within:block' => 'focus-within:block',
]);

