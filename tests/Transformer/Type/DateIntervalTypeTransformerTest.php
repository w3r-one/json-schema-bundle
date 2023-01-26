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

use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class DateIntervalTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = DateIntervalType::class;

    protected static $type = 'object';

    public function testWidgetChoice($parts = ['years', 'months', 'days'])
    {
        $this->common(self::$formType, self::$property, self::$type, 'date_interval_choice', null, [
            'widget' => 'choice',
            'with_years' => in_array('years', $parts),
            'with_weeks' => in_array('weeks', $parts),
            'with_months' => in_array('months', $parts),
            'with_days' => in_array('days', $parts),
            'with_hours' => in_array('hours', $parts),
            'with_minutes' => in_array('minutes', $parts),
            'with_seconds' => in_array('seconds', $parts),
        ]);
        $this->assertArrayHasKey('properties', $this->schema['properties'][self::$property]);
        $this->assertCount(\count($parts), $this->schema['properties'][self::$property]['properties']);

        foreach ($parts as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('choice', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
            $this->assertArrayHasKey('enum', $this->schema['properties'][self::$property]['properties'][$property]);
            $this->assertArrayHasKey('enumTitles', $this->schema['properties'][self::$property]['properties'][$property]['options']['choice']);
        }
    }

    public function testWidgetChoiceVariations()
    {
        $possibleParts = ['years', 'months', 'days', 'hours', 'minutes', 'seconds'];
        $possibleCombinations = [[]];

        foreach ($possibleParts as $part) {
            foreach ($possibleCombinations as $combination) {
                array_push($possibleCombinations, array_merge(array($part), $combination));
            }
        }
        $possibleCombinations = array_filter($possibleCombinations);
        foreach ($possibleCombinations as $possibleCombination) {
            $this->testWidgetChoice($possibleCombination);
        }
    }

    public function testWidgetChoiceOnlyWithWeeks()
    {
        $this->testWidgetChoice(['weeks']);
    }

    public function testWidgetText($parts = ['years', 'months', 'days'])
    {
        $this->common(self::$formType, self::$property, self::$type, 'date_interval_text', null, [
            'widget' => 'text',
            'with_years' => in_array('years', $parts),
            'with_weeks' => in_array('weeks', $parts),
            'with_months' => in_array('months', $parts),
            'with_days' => in_array('days', $parts),
            'with_hours' => in_array('hours', $parts),
            'with_minutes' => in_array('minutes', $parts),
            'with_seconds' => in_array('seconds', $parts),
        ]);
        $this->assertArrayHasKey('properties', $this->schema['properties'][self::$property]);
        $this->assertCount(\count($parts), $this->schema['properties'][self::$property]['properties']);

        foreach ($parts as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('text', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
        }
    }

    public function testWidgetTextVariations()
    {
        $possibleParts = ['years', 'months', 'days', 'hours', 'minutes', 'seconds'];
        $possibleCombinations = [[]];

        foreach ($possibleParts as $part) {
            foreach ($possibleCombinations as $combination) {
                array_push($possibleCombinations, array_merge(array($part), $combination));
            }
        }
        $possibleCombinations = array_filter($possibleCombinations);
        foreach ($possibleCombinations as $possibleCombination) {
            $this->testWidgetText($possibleCombination);
        }
    }

    public function testWidgetTextOnlyWithWeeks()
    {
        $this->testWidgetText(['weeks']);
    }

    public function testWidgetSingleText()
    {
        $this->common(self::$formType, self::$property, 'string', 'date_interval_single_text', null, [
            'widget' => 'single_text',
        ]);
    }
}