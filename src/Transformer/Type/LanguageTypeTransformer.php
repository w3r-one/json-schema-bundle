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

use W3rOne\JsonSchemaBundle\Transformer\ChoiceTransformer;
use Symfony\Component\Form\FormInterface;

class LanguageTypeTransformer extends ChoiceTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        if (null === ($form->getConfig()->getOption('w3r_one_json_schema')['widget'] ?? null)) {
            $schema['options']['widget'] = 'language';
        }
        $schema['options']['choice']['alpha3'] = $form->getConfig()->getOption('alpha3', false);

        return $schema;
    }
}
