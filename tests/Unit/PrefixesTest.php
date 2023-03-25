<?php


use YieldStudio\TailwindMerge\TailwindMergeConfig;

test('prefix working correctly')->twMerge([
    'tw-block tw-hidden' => 'tw-hidden',
    'block hidden' => 'block hidden',
    'tw-p-3 tw-p-2' => 'tw-p-2',
    'p-3 p-2' => 'p-3 p-2',
    '!tw-right-0 !tw-inset-0' => '!tw-inset-0',
    'hover:focus:!tw-right-0 focus:hover:!tw-inset-0' => 'focus:hover:!tw-inset-0',
], TailwindMergeConfig::default()->prefix('tw-'));
