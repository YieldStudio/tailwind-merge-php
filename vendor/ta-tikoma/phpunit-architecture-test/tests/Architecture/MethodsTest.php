<?php

declare(strict_types=1);

namespace tests\Architecture;

use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Architecture\Enums\ObjectType;
use tests\TestCase;

final class MethodsTest extends TestCase
{
    public function test_layer_method_incoming_arguments_not_from()
    {
        $tests = $this->layer()->leaveByNameStart('tests');
        $filters = $this->layer()->leaveByNameStart('PHPUnit\\Architecture\\Filters');

        $this->assertIncomingsNotFrom($filters, $tests);
    }

    public function test_layer_method_incoming_arguments_from()
    {
        $assertMethods = $this->layer()
            ->leaveByNameStart('PHPUnit\\Architecture\\Asserts')
            ->leaveByType(ObjectType::_TRAIT());

        $layerClass = $this->layer()
            ->leaveByNameStart(Layer::class)
            ->leaveByType(ObjectType::_CLASS());

        $this->assertIncomingsFrom($assertMethods, $layerClass);
    }

    public function test_layer_method_size()
    {
        $filters = $this->layer()->leaveByNameStart('PHPUnit\\Architecture\\Filters');

        $this->assertMethodSizeLessThan($filters, 20);
    }
}
