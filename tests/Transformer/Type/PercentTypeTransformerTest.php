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

use Symfony\Component\Form\Extension\Core\Type\PercentType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class PercentTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = PercentType::class;

    protected static $type = 'string';

    protected static $widget = 'percent';

    public function testTypeString()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, self::$defaultLayout);
    }

    public function testTypeInteger()
    {
        $this->common(self::$formType, self::$property, 'integer', self::$widget, self::$defaultLayout, ['type' => 'integer']);
    }

    public function testDefaultOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
        $this->assertArrayHasKey('type', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertArrayHasKey('roundingMode', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertArrayHasKey('scale', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertArrayHasKey('symbol', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertEquals('fractional', $this->schema['properties'][self::$property]['options'][self::$widget]['type']);
        $this->assertEquals(null, $this->schema['properties'][self::$property]['options'][self::$widget]['roundingMode']);
        $this->assertEquals(0, $this->schema['properties'][self::$property]['options'][self::$widget]['scale']);
        $this->assertEquals('%', $this->schema['properties'][self::$property]['options'][self::$widget]['symbol']);
    }

    public function testOverriddenOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'rounding_mode' => $defaultRoundingMode = \NumberFormatter::ROUND_DOWN,
            'scale' => $defaultScale = 2,
            'symbol' => $defaultSymbol = '**',
        ]);
        $this->assertEquals($defaultRoundingMode, $this->schema['properties'][self::$property]['options'][self::$widget]['roundingMode']);
        $this->assertEquals($defaultScale, $this->schema['properties'][self::$property]['options'][self::$widget]['scale']);
        $this->assertEquals($defaultSymbol, $this->schema['properties'][self::$property]['options'][self::$widget]['symbol']);
    }

    public function testData()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'data' => $data = '55',
        ]);
        $this->assertEquals($data, $this->schema['properties'][self::$property]['default']);
    }
}