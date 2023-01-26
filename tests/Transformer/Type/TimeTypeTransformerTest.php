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

use Symfony\Component\Form\Extension\Core\Type\TimeType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class TimeTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = TimeType::class;

    protected static $type = 'object';

    public function testWidgetChoice($timeParts = ['hour', 'minute', 'second'])
    {
        $this->common(self::$formType, self::$property, self::$type, 'time_choice', null, [
            'widget' => 'choice',
            'with_minutes' => in_array('minute', $timeParts),
            'with_seconds' => in_array('second', $timeParts),
        ]);
        $this->assertCount(\count($timeParts), $this->schema['properties'][self::$property]['properties']);

        foreach ($timeParts as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('choice', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
            $this->assertArrayHasKey('enum', $this->schema['properties'][self::$property]['properties'][$property]);
            $this->assertArrayHasKey('enumTitles', $this->schema['properties'][self::$property]['properties'][$property]['options']['choice']);
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
        $this->common(self::$formType, self::$property, self::$type, 'time_text', null, [
            'widget' => 'text',
            'with_minutes' => in_array('minute', $timeParts),
            'with_seconds' => in_array('second', $timeParts),
        ]);
        $this->assertCount(\count($timeParts), $this->schema['properties'][self::$property]['properties']);

        foreach ($timeParts as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('text', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
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
        $this->common(self::$formType, self::$property, 'string', 'time_single_text', null, [
            'widget' => 'single_text',
        ]);
    }

    public function testData()
    {
        $this->common(self::$formType, self::$property, self::$type, 'time_choice', null, [
            'widget' => 'choice',
            'data' => $data = new \DateTime('1986-04-30 18:14:00'),
        ]);
        $this->assertEquals($data, $this->schema['properties'][self::$property]['default']);
    }
}