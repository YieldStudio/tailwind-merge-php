<?php

declare(strict_types=1);

namespace Pest\Arch\Expectations;

use Pest\Arch\Blueprint;
use Pest\Arch\Collections\Dependencies;
use Pest\Arch\GroupArchExpectation;
use Pest\Arch\Options\LayerOptions;
use Pest\Arch\SingleArchExpectation;
use Pest\Arch\ValueObjects\Targets;
use Pest\Expectation;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @internal
 */
final class ToOnlyBeUsedIn
{
    /**
     * Creates an "ToOnlyBeUsedIn" expectation.
     *
     * @param  array<int, string>|string  $targets
     */
    public static function make(Expectation $expectation, array|string $targets): GroupArchExpectation
    {
        assert(is_string($expectation->value) || is_array($expectation->value));

        /** @var Expectation<array<int, string>|string> $expectation */
        $blueprint = Blueprint::make(
            Targets::fromExpectation($expectation),
            Dependencies::fromExpectationInput($targets),
        );

        return GroupArchExpectation::fromExpectations($expectation, [
            SingleArchExpectation::fromExpectation(
                $expectation,
                static function (LayerOptions $options) use ($blueprint): void {
                    $blueprint->expectToOnlyBeUsedIn(
                        $options,
                        static fn (string $value, string $notAllowedDependOn) => throw new ExpectationFailedException(
                            "Expecting '{$value}' not to be used on '{$notAllowedDependOn}'.",
                        ),
                    );
                },
            ),
            ToBeUsedIn::make($expectation, $targets),
        ]);
    }
}
