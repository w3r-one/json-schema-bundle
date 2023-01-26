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

use Symfony\Component\Form\Extension\Core\Type\RangeType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class RangeTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = RangeType::class;

    protected static $type = 'number';

    protected static $widget = 'range';

    public function testBase()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, self::$defaultLayout);
    }

    public function testData()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget, null, [
            'data' => $data = 28,
        ]);
        $this->assertEquals($data, $this->schema['properties'][self::$property]['default']);
    }
}