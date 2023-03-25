<?php


use YieldStudio\TailwindMerge\TailwindMergeConfig;

test('separator working correctly')->twMerge([
    'block hidden' => 'hidden',
    'p-3 p-2' => 'p-2',
    '!right-0 !inset-0' => '!inset-0',
    'hover_focus_!right-0 focus_hover_!inset-0' => 'focus_hover_!inset-0',
    'hover:focus:!right-0 focus:hover:!inset-0' => 'hover:focus:!right-0 focus:hover:!inset-0',
], TailwindMergeConfig::default()->separator('_'));


test('multiple character separator working correctly')->twMerge([
    'block hidden' => 'hidden',
    'p-3 p-2' => 'p-2',
    '!right-0 !inset-0' => '!inset-0',
    'hover__focus__!right-0 focus__hover__!inset-0' => 'focus__hover__!inset-0',
    'hover:focus:!right-0 focus:hover:!inset-0' => 'hover:focus:!right-0 focus:hover:!inset-0',
], TailwindMergeConfig::default()->separator('__'));
