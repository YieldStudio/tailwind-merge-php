<?php

namespace YieldStudio\TailwindMerge;

class ClassModifiersContext
{

    /**
     * @param array<string> $modifiers
     * @param bool $hasImportantModifier
     * @param string $baseClassName
     */
    public function __construct(
        public readonly array $modifiers,
        public readonly bool $hasImportantModifier,
        public readonly string $baseClassName,
    )
    {
    }

}