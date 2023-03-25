<?php

test('merges non-conflicting classes correctly')->twMerge([
    'border-t border-white/10' => 'border-t border-white/10',
    'border-t border-white' => 'border-t border-white',
    'text-3.5xl text-black' => 'text-3.5xl text-black',
]);
