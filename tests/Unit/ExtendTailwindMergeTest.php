<?php

use YieldStudio\TailwindMerge\TailwindMergeConfig;

$pluginA = function (TailwindMergeConfig $config) {
    return $config
        ->cacheSize(20)
        ->classGroups([
            ...$config->classGroups,
            'fooKey' =>
                [[
                    'fooKey' =>
                        ['bar', 'baz']]],
            'fooKey2' => [[
                'fooKey' =>
                    ['qux', 'quux']], 'other-2'],
            'otherKey' => ['nother', 'group'],
        ])
        ->conflictingClassGroups([
            ...$config->conflictingClassGroups,
            'fooKey' => ['otherKey'],
            'otherKey' => ['fooKey', 'fooKey2'],
        ]);
};

test('extendTailwindMerge works correctly with single config')->twMerge(classLists: [
    '' => '',
    'my-modifier:fooKey-bar my-modifier:fooKey-baz' => 'my-modifier:fooKey-baz',
    'other-modifier:fooKey-bar other-modifier:fooKey-baz' => 'other-modifier:fooKey-baz',
    'group fooKey-bar' => 'fooKey-bar',
    'fooKey-bar group' => 'group',
    'group other-2' => 'group other-2',
    'other-2 group' => 'group',
    'p-10 p-20' => 'p-20',
    'hover:focus:p-10 focus:hover:p-20' => 'focus:hover:p-20',
], plugins: [
    $pluginA
]);

test('extendTailwindMerge works corectly with multiple configs')->twMerge(classLists: [
    '' => '',
    'my-modifier:fooKey-bar my-modifier:fooKey-baz' => 'my-modifier:fooKey-baz',
    'other-modifier:hi-there other-modifier:hello' => 'other-modifier:hello',
    'group fooKey-bar' => 'fooKey-bar',
    'fooKey-bar group' => 'group',
    'group other-2' => 'group other-2',
    'other-2 group' => 'group',
    'p-10 p-20' => 'p-20',
    'hover:focus:p-10 focus:hover:p-20' => 'focus:hover:p-20',
], plugins: [
    $pluginA,
    function (TailwindMergeConfig $config) {
        return $config
            ->cacheSize(20)
            ->classGroups([
                ...$config->classGroups,
                'secondConfigKey' => ['hi-there', 'hello']
            ]);
    }
]);