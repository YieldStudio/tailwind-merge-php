<?php

namespace YieldStudio\TailwindMerge;

use Illuminate\Support\Collection;

class ClassPartObject
{
    /**
     * @var Collection<int, ClassPartObject>
     */
    public readonly Collection $nextPart;

    /**
     * @var Collection<int, ClassValidatorObject>
     */
    public readonly Collection $validators;

    public function __construct(public ?string $classGroupId = null)
    {

        $this->nextPart = new Collection();
        $this->validators = new Collection();
    }

    public function setClassGroupId(string $classGroupId): static {
        $this->classGroupId = $classGroupId;
        return $this;
    }

}