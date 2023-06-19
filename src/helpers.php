<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\TailwindMerge;

if (! function_exists('tw_merge')) {
    /**
     * @param array|string ...$classLists
     */
    function tw_merge(array|string ...$classLists): string
    {
        return TailwindMerge::instance()->merge(...$classLists);
    }
}
