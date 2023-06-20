<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge;

final class ClassPart
{
    /**
     * @var array<string, ClassPart>
     */
    public array $nextPart = [];

    /**
     * @var ClassValidator[]
     */
    public array $validators = [];

    public function __construct(public ?string $classGroupId = null)
    {
    }

    public function setClassGroupId(string $classGroupId): static
    {
        $this->classGroupId = $classGroupId;

        return $this;
    }
}
