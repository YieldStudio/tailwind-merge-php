<?php

it('basic arbitrary variants')->twMerge([
    '[&>*]:underline [&>*]:line-through' => '[&>*]:line-through',
    '[&>*]:underline [&>*]:line-through [&_div]:line-through' => '[&>*]:line-through [&_div]:line-through',
    'supports-[display:grid]:flex supports-[display:grid]:grid' => 'supports-[display:grid]:grid'
]);

it('arbitrary variants with modifiers')->twMerge([
    'dark:lg:hover:[&>*]:underline dark:lg:hover:[&>*]:line-through' => 'dark:lg:hover:[&>*]:line-through',
    'dark:lg:hover:[&>*]:underline dark:hover:lg:[&>*]:line-through' => 'dark:hover:lg:[&>*]:line-through',
    'hover:[&>*]:underline [&>*]:hover:line-through' => 'hover:[&>*]:underline [&>*]:hover:line-through',
    'hover:dark:[&>*]:underline dark:hover:[&>*]:underline dark:[&>*]:hover:line-through' => 'dark:hover:[&>*]:underline dark:[&>*]:hover:line-through',
    'dark:hover:[&>*]:underline dark:[&>*]:hover:line-through' => 'dark:hover:[&>*]:underline dark:[&>*]:hover:line-through',
]);

it('arbitrary variants with complex syntax in them')->twMerge([
    '[@media_screen{@media(hover:hover)}]:underline [@media_screen{@media(hover:hover)}]:line-through' => '[@media_screen{@media(hover:hover)}]:line-through',
    'hover:[@media_screen{@media(hover:hover)}]:underline hover:[@media_screen{@media(hover:hover)}]:line-through' => 'hover:[@media_screen{@media(hover:hover)}]:line-through',
]);

it('arbitrary variants with attribute selectors')->twMerge([
    '[&[data-open]]:underline [&[data-open]]:line-through' => '[&[data-open]]:line-through'
]);

it('arbitrary variants with multiple attribute selectors')->twMerge([
    '[&[data-foo][data-bar]:not([data-baz])]:underline [&[data-foo][data-bar]:not([data-baz])]:line-through' => '[&[data-foo][data-bar]:not([data-baz])]:line-through'
]);

it('multiple arbitrary variants')->twMerge([
    '[&>*]:[&_div]:underline [&>*]:[&_div]:line-through' => '[&>*]:[&_div]:line-through',
    '[&>*]:[&_div]:underline [&_div]:[&>*]:line-through' => '[&>*]:[&_div]:underline [&_div]:[&>*]:line-through',
    'hover:dark:[&>*]:focus:disabled:[&_div]:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through' => 'dark:hover:[&>*]:disabled:focus:[&_div]:line-through',
    'hover:dark:[&>*]:focus:[&_div]:disabled:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through' => 'hover:dark:[&>*]:focus:[&_div]:disabled:underline dark:hover:[&>*]:disabled:focus:[&_div]:line-through'
]);

it('arbitrary variants with arbitrary properties')->twMerge([
    '[&>*]:[color:red] [&>*]:[color:blue]' => '[&>*]:[color:blue]',
    '[&[data-foo][data-bar]:not([data-baz])]:nod:noa:[color:red] [&[data-foo][data-bar]:not([data-baz])]:noa:nod:[color:blue]' => '[&[data-foo][data-bar]:not([data-baz])]:noa:nod:[color:blue]',
]);
