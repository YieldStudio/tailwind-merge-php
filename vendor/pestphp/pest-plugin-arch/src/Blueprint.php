<?php

declare(strict_types=1);

namespace Pest\Arch;

use Pest\Arch\Collections\Dependencies;
use Pest\Arch\Factories\LayerFactory;
use Pest\Arch\Options\LayerOptions;
use Pest\Arch\Repositories\ObjectsRepository;
use Pest\Arch\Support\Composer;
use Pest\Arch\ValueObjects\Dependency;
use Pest\Arch\ValueObjects\Targets;
use PHPUnit\Architecture\ArchitectureAsserts;
use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @internal
 */
final class Blueprint
{
    use ArchitectureAsserts;

    /**
     * Creates a new Blueprint instance.
     */
    public function __construct(
        private readonly LayerFactory $layerFactory,
        private readonly Targets $target,
        private readonly Dependencies $dependencies
    ) {
        // ...
    }

    /**
     * Creates a new Blueprint instance.
     */
    public static function make(Targets $target, Dependencies $dependencies): self
    {
        $factory = new LayerFactory(ObjectsRepository::getInstance());

        return new self($factory, $target, $dependencies);
    }

    /**
     * Expects the target to use the given dependencies.
     *
     * @param  callable(string, string): mixed  $failure
     */
    public function expectToUse(LayerOptions $options, callable $failure): void
    {
        foreach ($this->target->value as $targetValue) {
            $targetLayer = $this->layerFactory->make($options, $targetValue);

            foreach ($this->dependencies->values as $dependency) {
                $dependencyLayer = $this->layerFactory->make($options, $dependency->value);

                try {
                    $this->assertDoesNotDependOn($targetLayer, $dependencyLayer);
                } catch (ExpectationFailedException) {
                    continue;
                }

                $failure($targetValue, $dependency->value);
            }
        }
    }

    /**
     * Expects the target to "only" use the given dependencies.
     *
     * @param  callable(string, string, string): mixed  $failure
     */
    public function expectToOnlyUse(LayerOptions $options, callable $failure): void
    {
        foreach ($this->target->value as $targetValue) {
            $allowedUses = array_merge(
                ...array_map(fn (Layer $layer): array => array_map(
                    // @phpstan-ignore-next-line
                    fn (ObjectDescription $object): string => $object->name, iterator_to_array($layer->getIterator())), array_map(
                        fn (string $dependency): Layer => $this->layerFactory->make($options, $dependency),
                        [$targetValue, ...array_map(
                            fn (Dependency $dependency): string => $dependency->value, $this->dependencies->values
                        )],
                    )
                ));

            $notDeclaredDependencies = [];

            foreach ($this->layerFactory->make($options, $targetValue) as $object) {
                assert($object instanceof ObjectDescription);

                foreach ($object->uses as $use) {
                    assert(is_string($use));

                    if (! in_array($use, $allowedUses, true)) {
                        $notDeclaredDependencies[] = $use;
                    }
                }
            }

            try {
                if ($notDeclaredDependencies !== []) {
                    throw new ExpectationFailedException($notDeclaredDependencies[0]);
                }

                self::assertTrue(true);
            } catch (ExpectationFailedException $e) {
                $failure($targetValue, $this->dependencies->__toString(), $e->getMessage());
            }
        }
    }

    /**
     * Expects the dependency to "only" be used by given targets.
     *
     * @param  callable(string, string): mixed  $failure
     */
    public function expectToOnlyBeUsedIn(LayerOptions $options, callable $failure): void
    {
        foreach (Composer::userNamespaces() as $namespace) {
            $namespaceLayer = $this->layerFactory->make($options, $namespace);

            foreach ($this->dependencies->values as $dependency) {
                $namespaceLayer = $namespaceLayer->excludeByNameStart($dependency->value);
            }

            foreach ($this->target->value as $targetValue) {
                $dependencyLayer = $this->layerFactory->make($options, $targetValue);

                try {
                    $this->assertDoesNotDependOn($namespaceLayer, $dependencyLayer);
                } catch (ExpectationFailedException) {
                    $objects = $this->getObjectsWhichUsesOnLayerAFromLayerB($namespaceLayer, $dependencyLayer);
                    [$dependOn, $target] = explode(' <- ', $objects[0]);

                    $failure($target, $dependOn);
                }
            }
        }
    }

    /**
     * Asserts that a condition is true.
     *
     * @throws ExpectationFailedException
     */
    public static function assertTrue(mixed $condition, string $message = ''): void
    {
        Assert::assertTrue($condition, $message);
    }

    /**
     * Asserts that two variables are not equal.
     *
     * @throws ExpectationFailedException
     */
    public static function assertNotEquals(mixed $expected, mixed $actual, string $message = ''): void
    {
        Assert::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two variables are equal.
     *
     * @throws ExpectationFailedException
     */
    public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void
    {
        Assert::assertEquals($expected, $actual, $message);
    }
}
