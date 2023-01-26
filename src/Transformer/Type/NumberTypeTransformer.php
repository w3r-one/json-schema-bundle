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

use W3rOne\JsonSchemaBundle\Transformer\StringTransformer;
use Symfony\Component\Form\FormInterface;

class NumberTypeTransformer extends StringTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        if ('number' === $form->getConfig()->getOption('input', 'number')) {
            $schema['type'] = 'number';
        }
        $schema['options']['number'] = [
            'scale' => $form->getConfig()->getOption('scale'),
            'roundingMode' => $form->getConfig()->getOption('rounding_mode', \NumberFormatter::ROUND_HALFUP),
            'input' => $form->getConfig()->getOption('input', 'number'),
        ];

        return $schema;
    }
}
