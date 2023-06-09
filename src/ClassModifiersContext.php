<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge;

final class ClassModifiersContext
{
    /**
     * @param $modifiers array<string>
     */
    public function __construct(
        public readonly array $modifiers,
        public readonly bool $hasImportantModifier,
        public readonly string $baseClassName,
        public readonly int|null $maybePostfixModifierPosition,
    ) {
    }
}
