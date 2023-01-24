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

class PercentTypeTransformer extends AbstractStringTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        if ('integer' === $form->getConfig()->getOption('type', 'fractional')) {
            $schema['type'] = 'integer';
        }
        $schema['options']['percent'] = [
            'roundingMode' => $form->getConfig()->getOption('rounding_mode', \NumberFormatter::ROUND_HALFUP),
            'scale' => $form->getConfig()->getOption('scale', 0),
            'symbol' => $form->getConfig()->getOption('symbol', '%'),
            'type' => $form->getConfig()->getOption('type', 'fractional'),
        ];

        return $schema;
    }
}
