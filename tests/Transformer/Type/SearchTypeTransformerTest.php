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

use Symfony\Component\Form\Extension\Core\Type\SearchType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class SearchTypeTransformerTest extends BaseTransformerTest
{
    protected static string $formType = SearchType::class;

    protected static string $type = 'string';

    protected static string $widget = 'search';

    public function testBase()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
    }

    public function testData()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'data' => $data = 'test',
        ]);
        $this->assertEquals($data, $this->schema['properties'][self::$property]['default']);
    }
}
