<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Strategy
    |--------------------------------------------------------------------------
    |
    | Configuration initialization strategy.
    | Set to `merge` to extend the default configuration.
    | Set to `replace` to override the default configuration.
    */

    'strategy' => 'merge',

    /*
    |--------------------------------------------------------------------------
    | LRU Cache Size
    |--------------------------------------------------------------------------
    |
    | Integer indicating size of LRU cache used for memoizing results.
    | Cache might be up to twice as big as `cacheSize`.
    | To disable cache, set to 0.
    */

    'cache_size' => 5000,

    /*
    |--------------------------------------------------------------------------
    | Separator
    |--------------------------------------------------------------------------
    |
    | Custom separator for modifiers in Tailwind classes.
    | See https://tailwindcss.com/docs/configuration#separator
    */

    'separator' => ':',

    /*
    |--------------------------------------------------------------------------
    | Prefix
    |--------------------------------------------------------------------------
    |
    | Prefix added to Tailwind-generated classes.
    | See https://tailwindcss.com/docs/configuration#prefix
    */

    'prefix' => null,

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | Theme scales used in classGroups.
    | The keys are the same as in the Tailwind config but the values are sometimes defined more broadly.
    */

    'theme' => [],

    /*
    |--------------------------------------------------------------------------
    | Class Groups
    |--------------------------------------------------------------------------
    |
    | Object with groups of classes.
    | Example : {
    |     // Creates group of classes `group`, `of` and `classes`
    |     'group-id': ['group', 'of', 'classes'],
    |     // Creates group of classes `look-at-me-other` and `look-at-me-group`.
    |     'other-group': [{ 'look-at-me': ['other', 'group']}]
    | }
    */

    'class_groups' => [],

    /*
    |--------------------------------------------------------------------------
    | Conflicting Class Groups
    |--------------------------------------------------------------------------
    |
    | Conflicting classes across groups.
    | The key is ID of class group which creates conflict, values are IDs of class groups which receive a conflict.
    | A class group is ID is the key of a class group in classGroups object.
    |
    | Example : { gap: ['gap-x', 'gap-y'] }
    */

    'conflicting_class_groups' => [],

    /*
    |--------------------------------------------------------------------------
    | Conflicting Class Group Modifiers
    |--------------------------------------------------------------------------
    |
    | A class group ID is the key of a class group in classGroups object.
    |
    | Example : { 'font-size': ['leading'] }
    */

    'conflicting_class_group_modifiers' => [],

];
