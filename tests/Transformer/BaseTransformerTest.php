<?php

/*
 * This file is part of the W3rOneJsonSchemaBundle.
 *
 * (c) w3r-one <contact@w3r.one>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3rOne\JsonSchemaBundle\Tests\Transformer;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use W3rOne\JsonSchemaBundle\Tests\JsonSchemaTestCase;

class BaseTransformerTest extends JsonSchemaTestCase
{
    protected static $property = 'property';

    public function test()
    {
        // to raise phpunit warning...
        $this->assertTrue(true);
    }

    public function process(FormInterface $form, string $property, string $type, string $widget, ?string $layout = null)
    {
        $this->schema = ($this->jsonSchema)($form);

        $this->assertTrue(is_array($this->schema));
        if (null === $form->getParent()) {
            $this->assertArrayHasKey('$schema', $this->schema);
            $this->assertArrayHasKey('$id', $this->schema);
            $this->assertEquals('object', $this->schema['type']);
            $this->assertEquals('form', $this->schema['title']);
        }
        $this->assertArrayHasKey('type', $this->schema);
        $this->assertArrayHasKey('title', $this->schema);
        $this->assertArrayHasKey('properties', $this->schema);
        $this->assertArrayHasKey('options', $this->schema);

        $this->assertArrayHasKey($property, $this->schema['properties']);
        $this->assertArrayHasKey('type', $this->schema['properties'][$property]);
        $this->assertArrayHasKey('title', $this->schema['properties'][$property]);
        $this->assertArrayHasKey('options', $this->schema['properties'][$property]);
        $this->assertArrayHasKey('widget', $this->schema['properties'][$property]['options']);
        $this->assertArrayHasKey('layout', $this->schema['properties'][$property]['options']);
        $this->assertEquals($type, $this->schema['properties'][$property]['type']);
        $this->assertEquals($widget, $this->schema['properties'][$property]['options']['widget']);
        $this->assertEquals($layout ?? self::$defaultLayout, $this->schema['properties'][$property]['options']['layout']);
    }

    public function common(string $formType, string $property, string $type, string $widget, ?string $layout = null, array $options = ['label' => 'label'])
    {
        if (!in_array($formType, [ButtonType::class, ResetType::class, SubmitType::class])) {
            // layout override
            $this->process($this->factory->create()->add($property, $formType, array_merge($options, [
                'w3r_one_json_schema' => [
                    'layout' => $layoutOverridden = 'two-cols',
                ],
            ])), $property, $type, $widget, $layoutOverridden);

            // widget override
            $this->process($this->factory->create()->add($property, $formType, array_merge($options, [
                'w3r_one_json_schema' => [
                    'widget' => $widgetOverridden = 'custom_widget',
                ],
            ])), $property, $type, $widgetOverridden, $layout);

            // extending custom
            $this->process($this->factory->create()->add($property, $formType, array_merge($options, [
                'w3r_one_json_schema' => [
                    ($optionKey = 'foo') => ($optionValue = 'bar'),
                ],
            ])), $property, $type, $widget, $layout);
            $this->assertArrayHasKey($optionKey, $this->schema['properties'][$property]['options']);
            $this->assertEquals($optionValue, $this->schema['properties'][$property]['options'][$optionKey]);

            // write-only
            $this->process($this->factory->create()->add($property, $formType, array_merge($options, [
                'mapped' => false,
            ])), $property, $type, $widget, $layout);
            $this->assertEquals(true, $this->schema['properties'][$property]['writeOnly']);
        }

        // read-only
        $this->process($this->factory->create()->add($property, $formType, array_merge($options, [
            'disabled' => true,
        ])), $property, $type, $widget, $layout);
        $this->assertEquals(true, $this->schema['properties'][$property]['readOnly']);

        // extending HTML attr
        $this->process($this->factory->create()->add($property, $formType, array_merge($options, [
            'attr' => [
                ($optionKey = 'foo') => ($optionValue = 'bar'),
            ],
        ])), $property, $type, $widget, $layout);
        $this->assertArrayHasKey('attr', $this->schema['properties'][$property]['options']);
        $this->assertArrayHasKey($optionKey, $this->schema['properties'][$property]['options']['attr']);
        $this->assertEquals($optionValue, $this->schema['properties'][$property]['options']['attr'][$optionKey]);

        // no label
        $this->process($this->factory->create()->add($property, $formType, array_merge($options, [
            'label' => false,
        ])), $property, $type, $widget, $layout);
        $this->assertEquals('', $this->schema['properties'][$property]['title']);

        // proper base test
        $this->process($this->factory->create()->add($property, $formType, $options), $property, $type, $widget, $layout);
    }
}