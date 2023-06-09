<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\TailwindMerge;
use YieldStudio\TailwindMerge\TailwindMergeConfig;
use YieldStudio\TailwindMerge\ThemeGetter;

test('handles arbitrary property conflicts correctly')->twMerge([
    '[paint-order:markers] [paint-order:normal]' => '[paint-order:normal]',
    '[paint-order:markers] [--my-var:2rem] [paint-order:normal] [--my-var:4px]' => '[paint-order:normal] [--my-var:4px]',
]);

test('handles arbitrary property conflicts with modifiers correctly')->twMerge([
    '[paint-order:markers] hover:[paint-order:normal]' => '[paint-order:markers] hover:[paint-order:normal]',
    'hover:[paint-order:markers] hover:[paint-order:normal]' => 'hover:[paint-order:normal]',
    'hover:focus:[paint-order:markers] focus:hover:[paint-order:normal]' => 'focus:hover:[paint-order:normal]',
    '[paint-order:markers] [paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]' => '[paint-order:normal] [--my-var:2rem] lg:[--my-var:4px]',
]);

test('handles complex arbitrary property conflicts correctly')->twMerge([
    '[-unknown-prop:::123:::] [-unknown-prop:url(https://hi.com)]' => '[-unknown-prop:url(https://hi.com)]',
]);

test('handles important modifier correctly')->twMerge([
    '![some:prop] [some:other]' => '![some:prop] [some:other]',
    '![some:prop] [some:other] [some:one] ![some:another]' => '[some:one] ![some:another]',
]);

test('handles simple conflicts with arbitrary values correctly')->twMerge([
    'm-[2px] m-[10px]' => 'm-[10px]',
    'm-[2px] m-[11svmin] m-[12in] m-[13lvi] m-[14vb] m-[15vmax] m-[16mm] m-[17%] m-[18em] m-[19px] m-[10dvh]' => 'm-[10dvh]',
    'h-[10px] h-[11cqw] h-[12cqh] h-[13cqi] h-[14cqb] h-[15cqmin] h-[16cqmax]' => 'h-[16cqmax]',
    'z-20 z-[99]' => 'z-[99]',
    'my-[2px] m-[10rem]' => 'm-[10rem]',
    'cursor-pointer cursor-[grab]' => 'cursor-[grab]',
    'm-[2px] m-[calc(100%-var(--arbitrary))]' => 'm-[calc(100%-var(--arbitrary))]',
    'm-[2px] m-[length:var(--mystery-var)]' => 'm-[length:var(--mystery-var)]',
    'opacity-10 opacity-[0.025]' => 'opacity-[0.025]',
    'scale-75 scale-[1.7]' => 'scale-[1.7]',
    'brightness-90 brightness-[1.75]' => 'brightness-[1.75]',
    'min-h-[0.5px] min-h-[0]' => 'min-h-[0]',
    'text-[0.5px] text-[color:0]' => 'text-[0.5px] text-[color:0]',
    'text-[0.5px] text-[--my-0]' => 'text-[0.5px] text-[--my-0]',
]);

test('handles arbitrary length conflicts with labels and modifiers correctly')->twMerge([
    'hover:m-[2px] hover:m-[length:var(--c)]' => 'hover:m-[length:var(--c)]',
    'hover:focus:m-[2px] focus:hover:m-[length:var(--c)]' => 'focus:hover:m-[length:var(--c)]',
    'border-b border-[color:rgb(var(--color-gray-500-rgb)/50%))]' => 'border-b border-[color:rgb(var(--color-gray-500-rgb)/50%))]',
    'border-[color:rgb(var(--color-gray-500-rgb)/50%))] border-b' => 'border-[color:rgb(var(--color-gray-500-rgb)/50%))] border-b',
    'border-b border-[color:rgb(var(--color-gray-500-rgb)/50%))] border-some-coloooor' => 'border-b border-some-coloooor',
]);

test('handles complex arbitrary value conflicts correctly')->twMerge([
    'grid-rows-[1fr,auto] grid-rows-2' => 'grid-rows-2',
    'grid-rows-[repeat(20,minmax(0,1fr))] grid-rows-3' => 'grid-rows-3',
]);

test('basic arbitrary variants')->twMerge([
    '[&>*]:underline [&>*]:line-through' => '[&>*]:line-through',
    '[&>*]:underline [&>*]:line-through [&_div]:line-through' => '[&>*]:line-through [&_div]:line-through',
    'supports-[display:grid]:flex supports-[display:grid]:grid' => 'supports-[display:grid]:grid',
]);

test('arbitrary variants with modifiers')->twMerge([
    'dark:lg:hover:[&>*]:underline dark:lg:hover:[&>*]:line-through' => 'dark:lg:hover:[&>*]:line-through',
    'dark:lg:hover:[&>*]:underline dark:hover:lg:[&>*]:line-through' => 'dark:hover:lg:[&>*]:line-through',
    'hover:[&>*]:underline [&>*]:hover:line-through' => 'hover:[&>*]:underline [&>*]:hover:line-through',
    'hover:dark:[&>*]:underline dark:hover:[&>*]:underline dark:[&>*]:hover:line-through' => 'dark:hover:[&>*]:underline dark:[&>*]:hover:line-through',
    'dark:hover:[&>*]:underline dark:[&>*]:hover:line-through' => 'dark:hover:[&>*]:underline dark:[&>*]:hover:line-through',
]);

test('arbitrary variants with complex syntax in them')->twMerge([
    '[@media_screen{@media(hover:hover)}]:underline [@media_screen{@media(hover:hover)}]:line-through' => '[@media_screen{@media(hover:hover)}]:line-through',
    'hover:[@media_screen{@media(hover:hover)}]:underline hover:[@media_screen{@media(hover:hover)}]:line-through' => 'hover:[@media_screen{@media(hover:hover)}]:line-through',
]);

test('arbitrary variants with attribute selectors')->twMerge([
    '[&[data-open]]:underline [&[data-open]]:line-through' => '[&[data-open]]:line-through',
]);

test('arbitrary variants with multiple attribute selectors')->twMerge([
    '[&[data-foo][data-bar]:not([data-baz])]:underline [&[data-foo][data-bar]:not([data-baz])]:line-through' => '[&[data-foo][data-bar]:not([data-baz])]:line-through',
]);

test('multiple arbitrary variants')->twMerge([
    '[&>*]:[&_div]:underline [&>*]:[&_div]:line-through' => '[&>*]:[&_div]:line-through',
    '[&>*]:[&_div]:underline [&_div]:[&>*]:line-through' => '[&>*]:[&_div]:underline [&_div]:[&>*]:line-through',
    'hover:dark:[&>*]:focus:disabled:[&_div]:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through' => 'dark:hover:[&>*]:disabled:focus:[&_div]:line-through',
    'hover:dark:[&>*]:focus:[&_div]:disabled:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through' => 'hover:dark:[&>*]:focus:[&_div]:disabled:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through',
]);

test('arbitrary variants with arbitrary properties')->twMerge([
    '[&>*]:[color:red] [&>*]:[color:blue]' => '[&>*]:[color:blue]',
    '[&[data-foo][data-bar]:not([data-baz])]:nod:noa:[color:red] [&[data-foo][data-bar]:not([data-baz])]:noa:nod:[color:blue]' => '[&[data-foo][data-bar]:not([data-baz])]:noa:nod:[color:blue]',
]);

test('merges classes from same group correctly')->twMerge([
    'overflow-x-auto overflow-x-hidden' => 'overflow-x-hidden',
    'basis-full basis-auto' => 'basis-auto',
    'w-full w-fit' => 'w-fit',
    'overflow-x-auto overflow-x-hidden overflow-x-scroll' => 'overflow-x-scroll',
    'overflow-x-auto hover:overflow-x-hidden overflow-x-scroll' => 'hover:overflow-x-hidden overflow-x-scroll',
    'overflow-x-auto hover:overflow-x-hidden hover:overflow-x-auto overflow-x-scroll' => 'hover:overflow-x-auto overflow-x-scroll',
]);

test('merges classes from Font Variant Numeric section correctly')->twMerge([
    'lining-nums tabular-nums diagonal-fractions' => 'lining-nums tabular-nums diagonal-fractions',
    'normal-nums tabular-nums diagonal-fractions' => 'tabular-nums diagonal-fractions',
    'tabular-nums diagonal-fractions normal-nums' => 'normal-nums',
    'tabular-nums proportional-nums' => 'proportional-nums',
]);

test('handles color conflicts properly')->twMerge([
    'bg-grey-5 bg-hotpink' => 'bg-hotpink',
    'hover:bg-grey-5 hover:bg-hotpink' => 'hover:bg-hotpink',
]);

test('handles conflicts across class groups correctly')->twMerge([
    'inset-1 inset-x-1' => 'inset-1 inset-x-1',
    'inset-x-1 inset-1' => 'inset-1',
    'inset-x-1 left-1 inset-1' => 'inset-1',
    'inset-x-1 inset-1 left-1' => 'inset-1 left-1',
    'inset-x-1 right-1 inset-1' => 'inset-1',
    'inset-x-1 right-1 inset-x-1' => 'inset-x-1',
    'inset-x-1 right-1 inset-y-1' => 'inset-x-1 right-1 inset-y-1',
    'right-1 inset-x-1 inset-y-1' => 'inset-x-1 inset-y-1',
    'inset-x-1 hover:left-1 inset-1' => 'hover:left-1 inset-1',
]);

test('ring and shadow classes do not create conflict')->twMerge([
    'ring shadow' => 'ring shadow',
    'ring-2 shadow-md' => 'ring-2 shadow-md',
    'shadow ring' => 'shadow ring',
    'shadow-md ring-2' => 'shadow-md ring-2',
]);

test('merges content utilities correctly')->twMerge([
    'content-[\'hello\'] content-[attr(data-content)]' => 'content-[attr(data-content)]',
]);

test('merges tailwind classes with important modifier correctly')->twMerge([
    '!font-medium !font-bold' => '!font-bold',
    '!font-medium !font-bold font-thin' => '!font-bold font-thin',
    '!right-2 !-inset-x-px' => '!-inset-x-px',
    'focus:!inline focus:!block' => 'focus:!block',
]);

test('conflicts across prefix modifiers')->twMerge([
    'text-lg/7 text-lg/8' => 'text-lg/8',
    'text-lg/none leading-9' => 'text-lg/none leading-9',
    'leading-9 text-lg/none' => 'text-lg/none',
    'w-full w-1/2' => 'w-1/2',
]);

test('conflicts across prefix custom modifiers')->twMerge(
    classLists: [
        'foo-1/2 foo-2/3' => 'foo-2/3',
        'bar-1 bar-2' => 'bar-2',
        'bar-1 baz-1' => 'bar-1 baz-1',
        'bar-1/2 bar-2' => 'bar-2',
        'bar-2 bar-1/2' => 'bar-1/2',
        'bar-1 baz-1/2' => 'baz-1/2',
    ],
    config: TailwindMergeConfig::default()
        ->classGroups([
            'foo' => ['foo-1/2', 'foo-2/3'],
            'bar' => ['bar-1', 'bar-2'],
            'baz' => ['baz-1', 'baz-2'],
        ], false)
        ->conflictingClassGroupModifiers([
            'baz' => ['bar'],
        ]),
);

test('conflicts across postfix modifiers')->twMerge([
    'hover:block hover:inline' => 'hover:inline',
    'hover:block hover:focus:inline' => 'hover:block hover:focus:inline',
    'hover:block hover:focus:inline focus:hover:inline' => 'hover:block focus:hover:inline',
    'focus-within:inline focus-within:block' => 'focus-within:block',
]);

test('handles negative value conflicts correctly')->twMerge([
    '-m-2 -m-5' => '-m-5',
    '-top-12 -top-2000' => '-top-2000',
]);

test('handles conflicts between positive and negative values correctly')->twMerge([
    '-m-2 m-auto' => 'm-auto',
    'top-12 -top-69' => '-top-69',
]);

test('handles conflicts across groups with negative values correctly')->twMerge([
    '-right-1 inset-x-1' => 'inset-x-1',
    'hover:focus:-right-1 focus:hover:inset-x-1' => 'focus:hover:inset-x-1',
]);

test('merges non-conflicting classes correctly')->twMerge([
    'border-t border-white/10' => 'border-t border-white/10',
    'border-t border-white' => 'border-t border-white',
    'text-3.5xl text-black' => 'text-3.5xl text-black',
]);

test('does not alter non-tailwind classes')->twMerge([
    'non-tailwind-class inline block' => 'non-tailwind-class block',
    'inline block inline-1' => 'block inline-1',
    'inline block i-inline' => 'block i-inline',
    'focus:inline focus:block focus:inline-1' => 'focus:block focus:inline-1',
]);

test('merges classes with per-side border colors correctly')->twMerge([
    'border-t-some-blue border-t-other-blue' => 'border-t-other-blue',
    'border-t-some-blue border-some-blue' => 'border-some-blue',
]);

test('prefix working correctly')->twMerge([
    'tw-block tw-hidden' => 'tw-hidden',
    'block hidden' => 'block hidden',
    'tw-p-3 tw-p-2' => 'tw-p-2',
    'p-3 p-2' => 'p-3 p-2',
    '!tw-right-0 !tw-inset-0' => '!tw-inset-0',
    'hover:focus:!tw-right-0 focus:hover:!tw-inset-0' => 'focus:hover:!tw-inset-0',
], TailwindMergeConfig::default()->prefix('tw-'));

test('handles pseudo variants conflicts properly')->twMerge([
    'empty:p-2 empty:p-3' => 'empty:p-3',
    'hover:empty:p-2 hover:empty:p-3' => 'hover:empty:p-3',
    'read-only:p-2 read-only:p-3' => 'read-only:p-3',
]);

test('handles pseudo variant group conflicts properly')->twMerge([
    'group-empty:p-2 group-empty:p-3' => 'group-empty:p-3',
    'peer-empty:p-2 peer-empty:p-3' => 'peer-empty:p-3',
    'group-empty:p-2 peer-empty:p-3' => 'group-empty:p-2 peer-empty:p-3',
    'hover:group-empty:p-2 hover:group-empty:p-3' => 'hover:group-empty:p-3',
    'group-read-only:p-2 group-read-only:p-3' => 'group-read-only:p-3',
]);

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

test('merges standalone classes from same group correctly')->twMerge([
    'inline block' => 'block',
    'hover:block hover:inline' => 'hover:inline',
    'hover:block hover:block' => 'hover:block',
    'inline hover:inline focus:inline hover:block hover:focus:block' => 'inline focus:inline hover:block hover:focus:block',
    'underline line-through' => 'line-through',
    'line-through no-underline' => 'no-underline',
]);

test('conditional classes', function () {
    $twMerge = new TailwindMerge();
    expect($twMerge->merge('foo', [
        'bar' => true,
        'noop' => false,
    ], 'test', [
        'test2' => true,
        'noop' => false,
    ]))->toBe('foo bar test test2');
});

test('theme scale can be extended')->twMerge(classLists: [
    'p-3 p-my-space p-my-margin' => 'p-my-space p-my-margin',
    'm-3 m-my-space m-my-margin' => 'm-my-margin',
], plugins: [
    function (TailwindMergeConfig $config) {
        return $config->theme([
            'spacing' => ['my-space'],
            'margin' => ['my-margin'],
        ]);
    },
]);

test('theme object can be extended')->twMerge(classLists: [
    'p-3 p-hello p-hallo' => 'p-3 p-hello p-hallo',
    'px-3 px-hello px-hallo' => 'px-hallo',
], plugins: [
    function (TailwindMergeConfig $config) {
        return $config
            ->theme([
                'my-theme' => ['hallo', 'hello'],
            ])
            ->classGroups([
                'px' => [['px' => [new ThemeGetter('my-theme')]]],
            ]);
    },
]);

test('createTailwindMerge works with single config function')->twMerge([
    '' => '',
    'my-modifier:fooKey-bar my-modifier:fooKey-baz' => 'my-modifier:fooKey-baz',
    'other-modifier:fooKey-bar other-modifier:fooKey-baz' => 'other-modifier:fooKey-baz',
    'group fooKey-bar' => 'fooKey-bar',
    'fooKey-bar group' => 'group',
    'group other-2' => 'group other-2',
    'other-2 group' => 'group',
], new TailwindMergeConfig(
    cacheSize: 20,
    theme: [],
    classGroups: [
        'fooKey' => [['fooKey' => ['bar', 'baz']]],
        'fooKey2' => [['fooKey' => ['qux', 'quux']], 'other-2'],
        'otherKey' => ['nother', 'group'],
    ],
    conflictingClassGroups: [
        'fooKey' => ['otherKey'],
        'otherKey' => ['fooKey', 'fooKey2'],
    ]
));

$pluginA = function (TailwindMergeConfig $config) {
    return $config
        ->cacheSize(20)
        ->classGroups([
            'fooKey' => [[
                'fooKey' => ['bar', 'baz']]],
            'fooKey2' => [[
                'fooKey' => ['qux', 'quux']], 'other-2'],
            'otherKey' => ['nother', 'group'],
        ])
        ->conflictingClassGroups([
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
    $pluginA,
]);

test('extendTailwindMerge works correctly with multiple configs')->twMerge(classLists: [
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
                'secondConfigKey' => ['hi-there', 'hello'],
            ]);
    },
]);

test('fully override config works correctly')->twMerge(classLists: [
    'block hidden' => 'hidden',
    'block inline-block' => 'block inline-block',
    'block inline-block hidden' => 'inline-block hidden',
    'shadow-sm shadow-lg' => 'shadow-sm shadow-lg',
], plugins: [
    function (TailwindMergeConfig $config) {
        return $config->classGroups(['display' => ['block', 'hidden']], false);
    },
]);

test('singleton works correctly', function () {
    expect(TailwindMerge::instance())->toBe(TailwindMerge::instance());
});

test('can extend instance', function () {
    $twMerge = TailwindMerge::instance()->extend(function (TailwindMergeConfig $config) {
        return $config->prefix('tw-');
    });

    expect($twMerge->merge('tw-hidden tw-block'))->toBe('tw-block');
});

test('supports Tailwind CSS v3.3 features')->twMerge([
    'text-red text-lg/7 text-lg/8' => 'text-red text-lg/8',
    'start-0 start-1 end-0 end-1 ps-0 ps-1 pe-0 pe-1 ms-0 ms-1 me-0 me-1 rounded-s-sm rounded-s-md rounded-e-sm rounded-e-md rounded-ss-sm rounded-ss-md rounded-ee-sm rounded-ee-md' => 'start-1 end-1 ps-1 pe-1 ms-1 me-1 rounded-s-md rounded-e-md rounded-ss-md rounded-ee-md',
    'start-0 end-0 inset-0 ps-0 pe-0 p-0 ms-0 me-0 m-0 rounded-ss rounded-es rounded-s' => 'inset-0 p-0 m-0 rounded-s',
    'hyphens-auto hyphens-manual' => 'hyphens-manual',
    'from-0% from-10% from-[12.5%] via-0% via-10% via-[12.5%] to-0% to-10% to-[12.5%]' => 'from-[12.5%] via-[12.5%] to-[12.5%]',
    'from-0% from-red' => 'from-0% from-red',
    'list-image-none list-image-[url(./my-image.png)] list-image-[var(--value)]' => 'list-image-[var(--value)]',
    'caption-top caption-bottom' => 'caption-bottom',
    'line-clamp-2 line-clamp-none line-clamp-[10]' => 'line-clamp-[10]',
    'delay-150 delay-0 duration-150 duration-0' => 'delay-0 duration-0',
    'justify-normal justify-center justify-stretch' => 'justify-stretch',
    'content-normal content-center content-stretch' => 'content-stretch',
    'whitespace-nowrap whitespace-break-spaces' => 'whitespace-break-spaces',
]);
