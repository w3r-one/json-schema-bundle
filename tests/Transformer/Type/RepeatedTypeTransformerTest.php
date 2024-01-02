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

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class RepeatedTypeTransformerTest extends BaseTransformerTest
{
    protected static string $formType = RepeatedType::class;

    protected static string $type = 'object';

    protected static string $widget = 'repeated';

    public function testBase()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'type' => PasswordType::class,
        ]);
        $this->assertArrayHasKey('properties', $this->schema['properties'][self::$property]);
        $this->assertCount(2, $this->schema['properties'][self::$property]['properties']);
        foreach (['first', 'second'] as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('password', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
            $this->assertEquals(true, $this->schema['properties'][self::$property]['properties'][$property]['options']['password']['alwaysEmpty']);
        }
    }

    public function testOverriddenOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'type' => TextType::class,
            'first_name' => $firstProperty = 'a',
            'second_name' => $secondProperty = 'b',
        ]);
        $this->assertArrayHasKey('properties', $this->schema['properties'][self::$property]);
        $this->assertCount(2, $this->schema['properties'][self::$property]['properties']);
        foreach ([$firstProperty, $secondProperty] as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('text', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
        }
    }
}
