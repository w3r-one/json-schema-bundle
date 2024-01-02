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

use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class CurrencyTypeTransformerTest extends BaseTransformerTest
{
    protected static string $formType = CurrencyType::class;

    protected static string $type = 'string';

    protected static string $widget = 'currency';

    public function testUnique(bool $expanded = false)
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'expanded' => $expanded,
            'multiple' => $multiple = false,
        ]);
        $this->assertArrayHasKey('enum', $this->schema['properties'][self::$property]);
        $this->assertArrayHasKey('enumTitles', $this->schema['properties'][self::$property]['options']['choice']);
        $this->assertArrayHasKey('expanded', $this->schema['properties'][self::$property]['options']['choice']);
        $this->assertArrayHasKey('multiple', $this->schema['properties'][self::$property]['options']['choice']);
        $this->assertEquals($expanded, $this->schema['properties'][self::$property]['options']['choice']['expanded']);
        $this->assertEquals($multiple, $this->schema['properties'][self::$property]['options']['choice']['multiple']);
    }

    public function testUniqueExpanded()
    {
        $this->testUnique(true);
    }

    public function testMultiple(bool $expanded = false)
    {
        $this->common(self::$formType, self::$property, 'array', self::$widget, null, [
            'expanded' => $expanded,
            'multiple' => $multiple = true,
        ]);
        $this->assertArrayHasKey('items', $this->schema['properties'][self::$property]);
        $this->assertArrayHasKey('enumTitles', $this->schema['properties'][self::$property]['options']['choice']);
        $this->assertArrayHasKey('expanded', $this->schema['properties'][self::$property]['options']['choice']);
        $this->assertArrayHasKey('multiple', $this->schema['properties'][self::$property]['options']['choice']);
        $this->assertArrayHasKey('type', $this->schema['properties'][self::$property]['items']);
        $this->assertEquals('string', $this->schema['properties'][self::$property]['items']['type']);
        $this->assertArrayHasKey('enum', $this->schema['properties'][self::$property]['items']);
        $this->assertEquals($expanded, $this->schema['properties'][self::$property]['options']['choice']['expanded']);
        $this->assertEquals($multiple, $this->schema['properties'][self::$property]['options']['choice']['multiple']);
    }

    public function testMultipleExpanded()
    {
        $this->testMultiple(true);
    }

    public function testOverriddenOptions()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'expanded' => false,
            'multiple' => false,
            'preferred_choices' => $preferredChoices = ['EUR', 'USD'],
        ]);
        $this->assertArrayHasKey('preferredChoices', $this->schema['properties'][self::$property]['options']['choice']);
        $this->assertCount(\count($preferredChoices), $this->schema['properties'][self::$property]['options']['choice']['preferredChoices']);
        $this->assertEquals($preferredChoices, $this->schema['properties'][self::$property]['options']['choice']['preferredChoices']);
    }
}
