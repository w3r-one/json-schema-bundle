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

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class DateTimeTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = DateTimeType::class;

    protected static $type = 'object';

    public function testWidgetChoice($timeParts = ['hour', 'minute', 'second'])
    {
        $this->common(self::$formType, self::$property, self::$type, 'date_time_choice', null, [
            'widget' => 'choice',
            'with_minutes' => in_array('minute', $timeParts),
            'with_seconds' => in_array('second', $timeParts),
        ]);
        $this->assertArrayHasKey('properties', $this->schema['properties'][self::$property]);
        $this->assertCount(2, $this->schema['properties'][self::$property]['properties']);
        $this->assertArrayHasKey('date', $this->schema['properties'][self::$property]['properties']);
        $this->assertCount(3, $this->schema['properties'][self::$property]['properties']['date']['properties']);
        $this->assertArrayHasKey('time', $this->schema['properties'][self::$property]['properties']);
        $this->assertCount(\count($timeParts), $this->schema['properties'][self::$property]['properties']['time']['properties']);

        foreach (['year', 'month', 'day'] as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']['date']['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties']['date']['properties'][$property]['type']);
            $this->assertEquals('choice', $this->schema['properties'][self::$property]['properties']['date']['properties'][$property]['options']['widget']);
            $this->assertArrayHasKey('enum', $this->schema['properties'][self::$property]['properties']['date']['properties'][$property]);
            $this->assertArrayHasKey('enumTitles', $this->schema['properties'][self::$property]['properties']['date']['properties'][$property]['options']['choice']);
        }
        foreach ($timeParts as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']['time']['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties']['time']['properties'][$property]['type']);
            $this->assertEquals('choice', $this->schema['properties'][self::$property]['properties']['time']['properties'][$property]['options']['widget']);
            $this->assertArrayHasKey('enum', $this->schema['properties'][self::$property]['properties']['time']['properties'][$property]);
            $this->assertArrayHasKey('enumTitles', $this->schema['properties'][self::$property]['properties']['time']['properties'][$property]['options']['choice']);
        }
    }

    public function testWidgetChoiceWithoutSeconds()
    {
        $this->testWidgetChoice(['hour', 'minute']);
    }

    public function testWidgetChoiceOnlyWithHours()
    {
        $this->testWidgetChoice(['hour']);
    }

    public function testWidgetText($timeParts = ['hour', 'minute', 'second'])
    {
        $this->common(self::$formType, self::$property, self::$type, 'date_time_text', null, [
            'widget' => 'text',
            'with_minutes' => in_array('minute', $timeParts),
            'with_seconds' => in_array('second', $timeParts),
        ]);
        $this->assertArrayHasKey('properties', $this->schema['properties'][self::$property]);
        $this->assertCount(2, $this->schema['properties'][self::$property]['properties']);
        $this->assertArrayHasKey('date', $this->schema['properties'][self::$property]['properties']);
        $this->assertCount(3, $this->schema['properties'][self::$property]['properties']['date']['properties']);
        $this->assertArrayHasKey('time', $this->schema['properties'][self::$property]['properties']);
        $this->assertCount(\count($timeParts), $this->schema['properties'][self::$property]['properties']['time']['properties']);

        foreach (['year', 'month', 'day'] as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']['date']['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties']['date']['properties'][$property]['type']);
            $this->assertEquals('text', $this->schema['properties'][self::$property]['properties']['date']['properties'][$property]['options']['widget']);
        }
        foreach ($timeParts as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']['time']['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties']['time']['properties'][$property]['type']);
            $this->assertEquals('text', $this->schema['properties'][self::$property]['properties']['time']['properties'][$property]['options']['widget']);
        }
    }

    public function testWidgetTextWithoutSeconds()
    {
        $this->testWidgetText(['hour', 'minute']);
    }

    public function testWidgetTextOnlyWithHours()
    {
        $this->testWidgetText(['hour']);
    }

    public function testWidgetSingleText()
    {
        $this->common(self::$formType, self::$property, 'string', 'date_time_single_text', null, [
            'widget' => 'single_text',
        ]);
    }

    public function testData()
    {
        $this->common(self::$formType, self::$property, self::$type, 'date_time_choice', null, [
            'widget' => 'choice',
            'data' => $data = new \DateTime('1986-04-30'),
        ]);
        $this->assertEquals($data, $this->schema['properties'][self::$property]['default']);
    }
}