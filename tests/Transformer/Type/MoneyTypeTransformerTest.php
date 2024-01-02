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

use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class MoneyTypeTransformerTest extends BaseTransformerTest
{
    protected static string $formType = MoneyType::class;

    protected static string $type = 'string';

    protected static string $widget = 'money';

    public function testBase()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
    }

    public function testDefaultOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
        $this->assertArrayHasKey('currency', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertArrayHasKey('divisor', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertArrayHasKey('roundingMode', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertArrayHasKey('scale', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertEquals('EUR', $this->schema['properties'][self::$property]['options'][self::$widget]['currency']);
        $this->assertEquals(1, $this->schema['properties'][self::$property]['options'][self::$widget]['divisor']);
        $this->assertEquals(\NumberFormatter::ROUND_HALFUP, $this->schema['properties'][self::$property]['options'][self::$widget]['roundingMode']);
        $this->assertEquals(2, $this->schema['properties'][self::$property]['options'][self::$widget]['scale']);
    }

    public function testOverriddenOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'currency' => $defaultCurrency = 'USD',
            'divisor' => $defaultDivisor = 100,
            'rounding_mode' => $defaultRoundingMode = \NumberFormatter::ROUND_DOWN,
            'scale' => $defaultScale = 3,
        ]);
        $this->assertEquals($defaultCurrency, $this->schema['properties'][self::$property]['options'][self::$widget]['currency']);
        $this->assertEquals($defaultDivisor, $this->schema['properties'][self::$property]['options'][self::$widget]['divisor']);
        $this->assertEquals($defaultRoundingMode, $this->schema['properties'][self::$property]['options'][self::$widget]['roundingMode']);
        $this->assertEquals($defaultScale, $this->schema['properties'][self::$property]['options'][self::$widget]['scale']);
    }

    public function testData()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'data' => $data = '100',
        ]);
        $this->assertEquals($data, $this->schema['properties'][self::$property]['default']);
    }
}
