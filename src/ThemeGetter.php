<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge;

final class ThemeGetter
{
    public function __construct(public readonly string $key)
    {
    }

    public function execute(array $theme): array
    {
        return $theme[$this->key] ?? [];
    }
}
