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

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class NumberTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = NumberType::class;

    protected static $type = 'number';

    protected static $widget = 'number';

    public function testInputNumber()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, self::$defaultLayout);
    }

    public function testInputString()
    {
        $this->common(self::$formType, self::$property, 'string', self::$widget, self::$defaultLayout, ['input' => 'string']);
    }

    public function testDefaultOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
        $this->assertArrayHasKey('input', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertArrayHasKey('roundingMode', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertArrayHasKey('scale', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertEquals('number', $this->schema['properties'][self::$property]['options'][self::$widget]['input']);
        $this->assertEquals(\NumberFormatter::ROUND_HALFUP, $this->schema['properties'][self::$property]['options'][self::$widget]['roundingMode']);
        $this->assertEquals(null, $this->schema['properties'][self::$property]['options'][self::$widget]['scale']);
    }

    public function testOverriddenOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'rounding_mode' => $defaultRoundingMode = \NumberFormatter::ROUND_DOWN,
            'scale' => $defaultScale = 1,
        ]);
        $this->assertEquals($defaultRoundingMode, $this->schema['properties'][self::$property]['options'][self::$widget]['roundingMode']);
        $this->assertEquals($defaultScale, $this->schema['properties'][self::$property]['options'][self::$widget]['scale']);
    }
}