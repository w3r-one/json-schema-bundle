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

use Symfony\Component\Form\Extension\Core\Type\ResetType;
use W3rOne\JsonSchemaBundle\Tests\Transformer\BaseTransformerTest;

class ResetTypeTransformerTest extends BaseTransformerTest
{
    protected static $formType = ResetType::class;

    protected static $type = 'boolean';

    protected static $widget = 'reset';

    public function testBase()
    {
        $this->common(self::$formType, self::$property, self::$type, self::$widget);
    }
}