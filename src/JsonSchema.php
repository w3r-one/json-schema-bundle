<?php

/*
 * This file is part of the W3rOneJsonSchemaBundle.
 *
 * (c) w3r-one <contact@w3r.one>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3rOne\JsonSchemaBundle;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;

class JsonSchema
{
    private $resolver;

    public function __construct(Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function __invoke(FormInterface $form): array
    {
        $schema = $this->resolver->resolve($form)->transform($form);

        return $this->recursiveTransform($form, $schema);
    }

    private function recursiveTransform(FormInterface $form, array &$schema): array
    {
        $guessedTypes = [get_class($form->getConfig()->getType()->getInnerType()), $form->getConfig()->getType()->getInnerType()->getParent()];
        $excludedTypes = [ChoiceType::class, CollectionType::class];

        // exclude ChoiceType (custom Transformer from the FormView) & CollectionType (custom Transformer from the prototype)
        if (empty(array_intersect($guessedTypes, $excludedTypes))) {
            foreach($form->all() as $child) {
                $schema['properties'][$child->getName()] = $this->resolver->resolve($child)->transform($child);

                if (!empty($child->all())) {
                    $this->recursiveTransform($child, $schema['properties'][$child->getName()]);
                }
            }
        }

        return $schema;
    }
}
