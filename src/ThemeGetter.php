<?php

namespace YieldStudio\TailwindMerge;

class ThemeGetter
{

    public function __construct(public readonly string $key) {
    }

    public function execute(array $theme) {
        return $theme[$this->key] ?? [];
    }

}