<?php

/*
 * This file is part of the W3rOneJsonSchemaBundle.
 *
 * (c) w3r-one <contact@w3r.one>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3rOne\JsonSchemaBundle\Tests\Transformer\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class CheckboxTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = CheckboxType::class;

    protected static $type = 'boolean';

    protected static $widget = 'checkbox';

    public function testBase()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
    }

    public function testDefaultOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
        $this->assertArrayHasKey('value', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertArrayHasKey('falseValues', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertEquals(1, $this->schema['properties'][self::$property]['options'][self::$widget]['value']);
        $this->assertEquals([null], $this->schema['properties'][self::$property]['options'][self::$widget]['falseValues']);
    }

    public function testOverriddenOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'value' => $defaultValue = 'yes',
            'false_values' => $defaultFalseValues = ['no'],
        ]);
        $this->assertEquals($defaultValue, $this->schema['properties'][self::$property]['options'][self::$widget]['value']);
        $this->assertEquals($defaultFalseValues, $this->schema['properties'][self::$property]['options'][self::$widget]['falseValues']);
    }
}