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
use W3rOne\JsonSchemaBundle\Transformer\ArrayTransformer;
use Symfony\Component\Form\FormInterface;

class CollectionTypeTransformer extends ArrayTransformer
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
            foreach($formItem->all() as $formProperty) {
                $schema['items']['properties'][$formProperty->getName()] = $this->resolver->resolve($formProperty)->transform($formProperty);
                $schema['items']['title'] = ''; // remove '__name_label__'

                if (!empty($formProperty->all())) {
                    $this->recursiveTransform($formProperty, $schema['items']['properties'][$formProperty->getName()]);
                }
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
            foreach($entryType->all() as $formProperty) {
                $schema['items']['properties'][$formProperty->getName()] = $this->resolver->resolve($formProperty)->transform($formProperty);
                $schema['items']['title'] = ''; // remove '__name_label__'

                if (!empty($formProperty->all())) {
                    $this->recursiveTransform($formProperty, $schema['items']['properties'][$formProperty->getName()]);
                }
            }
        }

        return $schema;
    }

    public function recursiveTransform(FormInterface $form, array &$schema): array
    {
        foreach($form->all() as $child) {
            $schema['properties'][$child->getName()] = $this->resolver->resolve($child)->transform($child);

            if (!empty($child->all())) {
                $this->recursiveTransform($child, $schema['properties'][$child->getName()]);
            }
        }

        return $schema;
    }
}
