<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Interfaces;

interface RuleInterface
{
    public function execute(string $value): bool;
}
