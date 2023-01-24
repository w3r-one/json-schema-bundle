<?php

/*
 * This file is part of the W3rOneJsonSchemaBundle.
 *
 * (c) w3r-one <contact@w3r.one>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3rOne\JsonSchemaBundle\Transformer\Type;

use W3rOne\JsonSchemaBundle\Transformer\AbstractStringTransformer;
use Symfony\Component\Form\FormInterface;

class RadioTypeTransformer extends AbstractStringTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        $schema['options']['radio'] = [
            'value' => $form->getConfig()->getOption('value', 1),
            'falseValues' => $form->getConfig()->getOption('false_values', [null]),
        ];

        return $schema;
    }
}
