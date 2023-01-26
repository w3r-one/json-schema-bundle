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

use Symfony\Component\Form\Extension\Core\Type\WeekType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class WeekTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = WeekType::class;

    protected static $type = 'object';

    public function testWidgetChoice()
    {
        $this->common(self::$formType, self::$property, self::$type, 'week_choice', null, [
            'widget' => 'choice',
        ]);
        $this->assertCount(2, $this->schema['properties'][self::$property]['properties']);

        foreach (['year', 'week'] as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('string', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('choice', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
            $this->assertArrayHasKey('enum', $this->schema['properties'][self::$property]['properties'][$property]);
            $this->assertArrayHasKey('enumTitles', $this->schema['properties'][self::$property]['properties'][$property]['options']['choice']);
        }
    }

    public function testWidgetText()
    {
        $this->common(self::$formType, self::$property, self::$type, 'week_text', null, [
            'widget' => 'text',
        ]);
        $this->assertCount(2, $this->schema['properties'][self::$property]['properties']);

        foreach (['year', 'week'] as $property) {
            $this->assertArrayHasKey($property, $this->schema['properties'][self::$property]['properties']);
            $this->assertEquals('integer', $this->schema['properties'][self::$property]['properties'][$property]['type']);
            $this->assertEquals('integer', $this->schema['properties'][self::$property]['properties'][$property]['options']['widget']);
        }
    }

    public function testWidgetSingleText()
    {
        $this->common(self::$formType, self::$property, 'string', 'week_single_text', null, [
            'widget' => 'single_text',
        ]);
    }
}