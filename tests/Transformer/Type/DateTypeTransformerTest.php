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

use Symfony\Component\Form\Extension\Core\Type\DateType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class DateTypeTransformerTest extends BaseTransformerTest
{
    protected static string $formType = DateType::class;

    protected static string $type = 'object';

    public function testWidgetChoice()
    {
        $this->common(self::$formType, self::$property, self::$type, 'date_choice', null, [
            'widget' => 'choice',
        ]);
        $this->assertArrayHasKey('properties', $this->schema['properties'][self::$property]);
        $this->assertCount(3, $this->schema['properties'][self::$property]['properties']);
        foreach (['year', 'month', 'day'] as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('choice', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
            $this->assertArrayHasKey('enum', $this->schema['properties'][self::$property]['properties'][$property]);
            $this->assertArrayHasKey('enumTitles', $this->schema['properties'][self::$property]['properties'][$property]['options']['choice']);
        }
    }

    public function testWidgetText()
    {
        $this->common(self::$formType, self::$property, self::$type, 'date_text', null, [
            'widget' => 'text',
        ]);
        $this->assertArrayHasKey('properties', $this->schema['properties'][self::$property]);
        $this->assertCount(3, $this->schema['properties'][self::$property]['properties']);
        foreach (['year', 'month', 'day'] as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('text', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
        }
    }

    public function testWidgetSingleText()
    {
        $this->common(self::$formType, self::$property, 'string', 'date_single_text', null, [
            'widget' => 'single_text',
        ]);
    }

    public function testData()
    {
        $this->common(self::$formType, self::$property, self::$type, 'date_choice', null, [
            'widget' => 'choice',
            'data' => $data = new \DateTime('1986-04-30'),
        ]);
        $this->assertEquals($data, $this->schema['properties'][self::$property]['default']);
    }
}
