<?php

use YieldStudio\TailwindMerge\TailwindMerge;

function tw_merge(...$classLists)
{
    return TailwindMerge::shared()->merge(...$classLists);
}