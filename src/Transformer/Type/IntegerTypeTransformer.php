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

use W3rOne\JsonSchemaBundle\Transformer\AbstractIntegerTransformer;
use Symfony\Component\Form\FormInterface;

class IntegerTypeTransformer extends AbstractIntegerTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        $schema['options']['integer']['roundingMode'] = $form->getConfig()->getOption('rounding_mode', \NumberFormatter::ROUND_DOWN);

        return $schema;
    }
}
