<?php

use YieldStudio\TailwindMerge\TailwindMerge;

if (!function_exists('tw_merge')) {
    function tw_merge(...$classLists)
    {
        return (new TailwindMerge)->merge(...$classLists);
    }
}
