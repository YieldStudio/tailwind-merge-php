<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge\Exceptions;

use Exception;
use YieldStudio\TailwindMerge\TailwindMergeConfig;

final class BadThemeException extends Exception
{
    public function __construct(string $message, public readonly TailwindMergeConfig $config)
    {
        parent::__construct($message);
    }
}
