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

use Symfony\Component\Form\Extension\Core\Type\UuidType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class UuidTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = UuidType::class;

    protected static $type = 'string';

    protected static $widget = 'uuid';

    public function testBase()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
    }
}