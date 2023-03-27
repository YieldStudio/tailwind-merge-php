<?php

namespace YieldStudio\TailwindMerge\Interfaces;

interface RuleInterface
{

    public function execute(string $value): bool;

}