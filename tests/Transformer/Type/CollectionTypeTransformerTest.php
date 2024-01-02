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

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use W3rOne\JsonSchemaBundle\Exception\TransformerException;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class CollectionTypeTransformerTest extends BaseTransformerTest
{
    protected static string $formType = CollectionType::class;

    protected static string $type = 'array';

    protected static string $widget = 'collection';

    public function testBase()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'entry_type' => EmailType::class,
            'allow_add' => true,
            'allow_delete' => true,
        ]);
        $this->assertArrayHasKey('items', $this->schema['properties'][self::$property]);
        $this->assertEquals('string', $this->schema['properties'][self::$property]['items']['type']);
        $this->assertEquals('email', $this->schema['properties'][self::$property]['items']['options']['widget']);
    }

    public function testWithoutPrototype()
    {
        $this->expectException(TransformerException::class);
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'entry_type' => EmailType::class,
            'allow_add' => false,
            'allow_delete' => true,
        ]);
    }
}
