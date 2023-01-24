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

use W3rOne\JsonSchemaBundle\Transformer\AbstractObjectTransformer;
use Symfony\Component\Form\FormInterface;
use W3rOne\JsonSchemaBundle\Utils;

class AutocompleteTypeTransformer extends AbstractObjectTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        $schema['options']['autocomplete'] = [
            'entity' => Utils::substrAfterLastDelimiter($form->getConfig()->getOption('ac_class'), '\\'),
            'id' => $form->getConfig()->getOption('ac_id'),
            'label' => $form->getConfig()->getOption('ac_label'),
            'params' => $form->getConfig()->getOption('ac_params'),
        ];

        return $schema;
    }
}
