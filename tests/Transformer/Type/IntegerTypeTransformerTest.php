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

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class IntegerTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = IntegerType::class;

    protected static $type = 'integer';

    protected static $widget = 'integer';

    public function testBase()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, self::$defaultLayout);
    }

    public function testDefaultOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
        $this->assertArrayHasKey('roundingMode', $this->schema['properties'][self::$property]['options'][self::$widget]);
        $this->assertEquals(\NumberFormatter::ROUND_DOWN, $this->schema['properties'][self::$property]['options'][self::$widget]['roundingMode']);
    }

    public function testOverriddenOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'rounding_mode' => $defaultRoundingMode = \NumberFormatter::ROUND_UP,
        ]);
        $this->assertEquals($defaultRoundingMode, $this->schema['properties'][self::$property]['options'][self::$widget]['roundingMode']);
    }
}