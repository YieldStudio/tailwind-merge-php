<?php

declare(strict_types=1);

namespace Pest\Arch\Contracts;

use Pest\Expectation;

/**
 * @internal
 *
 * @mixin Expectation<string>
 */
interface ArchExpectation
{
    /**
     * Ignores the given "targets" or "dependencies".
     *
     * @param  array<int, string>|string  $targetsOrDependencies
     * @return $this
     */
    public function ignoring(array|string $targetsOrDependencies): self;

    /**
     * Ignores global "user defined" functions.
     *
     * @return $this
     */
    public function ignoringGlobalFunctions(): self;
}
