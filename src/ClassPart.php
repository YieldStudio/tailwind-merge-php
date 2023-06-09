<?php

declare(strict_types=1);

namespace YieldStudio\TailwindMerge;

use Illuminate\Support\Collection;

final class ClassPart
{
    /**
     * @var Collection<string, ClassPart>
     */
    public readonly Collection $nextPart;

    /**
     * @var Collection<string, ClassValidator>
     */
    public readonly Collection $validators;

    public function __construct(public ?string $classGroupId = null)
    {

        $this->nextPart = new Collection();
        $this->validators = new Collection();
    }

    public function setClassGroupId(string $classGroupId): static
    {
        $this->classGroupId = $classGroupId;

        return $this;
    }
}
