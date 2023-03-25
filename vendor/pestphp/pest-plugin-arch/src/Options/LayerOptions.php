<?php

declare(strict_types=1);

namespace Pest\Arch\Options;

use Pest\Arch\SingleArchExpectation;

/**
 * @internal
 */
final class LayerOptions
{
    /**
     * @param  array<int, string>  $exclude
     */
    private function __construct(public readonly array $exclude)
    {
        // ...
    }

    /**
     * Creates a new Layer Options instance, with the context of the given expectation.
     */
    public static function fromExpectation(SingleArchExpectation $expectation): self
    {
        $exclude = array_merge(
            test()->arch()->ignore, // @phpstan-ignore-line
            $expectation->ignoring,
        );

        return new self($exclude);
    }
}
