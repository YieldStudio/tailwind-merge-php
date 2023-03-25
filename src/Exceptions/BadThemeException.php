<?php

namespace YieldStudio\TailwindMerge\Exceptions;

use Exception;
use YieldStudio\TailwindMerge\TailwindMergeConfig;

class BadThemeException extends Exception
{
    public function __construct(string $message, public readonly TailwindMergeConfig $config) {
        parent::__construct($message);
    }
}