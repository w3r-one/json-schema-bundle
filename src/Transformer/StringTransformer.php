<?php

/*
 * This file is part of the W3rOneJsonSchemaBundle.
 *
 * (c) w3r-one <contact@w3r.one>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3rOne\JsonSchemaBundle\Transformer;

use Symfony\Component\Form\FormInterface;

class AbstractStringTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        $schema['type'] = 'string';
        # TODO: try to guess minLength|maxLength

        return $schema;
    }
}
