<?php

namespace YieldStudio\TailwindMerge\Interfaces;

interface ValidatorInterface
{

    public function execute(string $value): bool;

}