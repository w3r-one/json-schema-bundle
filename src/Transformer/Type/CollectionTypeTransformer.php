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

use W3rOne\JsonSchemaBundle\Exception\TransformerException;
use W3rOne\JsonSchemaBundle\Transformer\AbstractArrayTransformer;
use Symfony\Component\Form\FormInterface;

class CollectionTypeTransformer extends AbstractArrayTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        $schema['options']['collection'] = [
            'allowAdd' => $form->getConfig()->getOption('allow_add', false),
            'allowDelete' => $form->getConfig()->getOption('allow_delete', false),
        ];

        foreach($form->all() as $formItem) {
            $schema['items'] = $this->resolver->resolve($formItem)->transform($formItem);
            $schema['items']['title'] = '';
            foreach($formItem->all() as $formProperty) {
                $schema['items']['properties'][$formProperty->getName()] = $this->resolver->resolve($formProperty)->transform($formProperty);
            }

            break;
        }

        // if no children hydrated, check the prototype to guess the structure
        if (empty($schema['items'])) {
            /** @var FormInterface $entryType */
            if (null === ($entryType = $form->getConfig()->getAttribute('prototype'))) {
                throw new TransformerException(sprintf('Unable to guess a schema for type `%s` on property `%s`. An empty collection should have `allow_add` to check the prototype.', get_class($form->getConfig()->getType()->getInnerType()), $form->getName()));
            }

            $schema['items'] = $this->resolver->resolve($entryType)->transform($entryType);
            $schema['items']['title'] = '';
            foreach($entryType->all() as $formProperty) {
                $schema['items']['properties'][$formProperty->getName()] = $this->resolver->resolve($formProperty)->transform($formProperty);
            }
        }

        return $schema;
    }
}
