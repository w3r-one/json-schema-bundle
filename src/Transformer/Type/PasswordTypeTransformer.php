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

class PasswordTypeTransformer extends StringTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        if (null !== ($alwaysEmpty = $form->getConfig()->getOption('always_empty'))) {
            $schema['options']['password']['alwaysEmpty'] = $alwaysEmpty;
        }

        return $schema;
    }
}
