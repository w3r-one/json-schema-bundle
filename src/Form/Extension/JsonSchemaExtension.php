<?php

/*
 * This file is part of the W3rOneJsonSchemaBundle.
 *
 * (c) w3r-one <contact@w3r.one>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3rOne\JsonSchemaBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class JsonSchemaExtension extends AbstractTypeExtension
{
    /**
     * @inheritdoc
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(['w3r_one_json_schema']);
    }
}
