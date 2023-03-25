<?php

use YieldStudio\TailwindMerge\TailwindMergeConfig;
use YieldStudio\TailwindMerge\ThemeGetter;

test('theme scale can be extended')->twMerge(classLists: [
    'p-3 p-my-space p-my-margin' => 'p-my-space p-my-margin',
    'm-3 m-my-space m-my-margin' => 'm-my-margin',
], plugins: [
    function (TailwindMergeConfig $config) {
        return $config->theme([
            ...$config->theme,
            'spacing' => [...$config->theme['spacing'], 'my-space'],
            'margin' => [...$config->theme['margin'], 'my-margin'],
        ]);
    }
]);

test('theme object can be extended')->twMerge(classLists: [
    'p-3 p-hello p-hallo' => 'p-3 p-hello p-hallo',
    'px-3 px-hello px-hallo' => 'px-hallo',
], plugins: [
    function (TailwindMergeConfig $config) {
        return $config
            ->theme([
                ...$config->theme,
                'my-theme' => ['hallo', 'hello'],
            ])
            ->classGroups([
                ...$config->classGroups,
                'px' => [
                    ...$config->classGroups['px'],
                    [ 'px' => [new ThemeGetter('my-theme')] ]
                ],
            ]);
    }
]);
