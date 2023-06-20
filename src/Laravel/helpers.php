<?php

declare(strict_types=1);

use YieldStudio\TailwindMerge\Laravel\Facades\TailwindMerge;

if (! function_exists('tw')) {
    /**
     * @param array|string ...$classLists
     */
    function tw(array|string ...$classLists): string
    {
        /** @var TailwindMerge $twMerge */
        $twMerge = app('tailwind-merge');

        return $twMerge->merge(...$classLists);
    }
}
