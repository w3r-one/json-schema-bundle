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

class MoneyTypeTransformer extends StringTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        $schema['options']['money'] = [
            'currency' => $form->getConfig()->getOption('currency', 'EUR'),
            'divisor' => $form->getConfig()->getOption('divisor', 1),
            'roundingMode' => $form->getConfig()->getOption('rounding_mode', \NumberFormatter::ROUND_HALFUP),
            'scale' => $form->getConfig()->getOption('scale', 2),
        ];

        return $schema;
    }
}
