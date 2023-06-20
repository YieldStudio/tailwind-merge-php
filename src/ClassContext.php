<?php


declare(strict_types=1);

namespace YieldStudio\TailwindMerge;

final class ClassContext
{
    public function __construct(
        public bool    $isTailwindClass,
        public string  $originalClassName,
        public bool    $hasPostfixModifier = false,
        public ?string $modifierId = null,
        public ?string $classGroupId = null,
    ) {
    }
}
