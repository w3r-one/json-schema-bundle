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

use W3rOne\JsonSchemaBundle\Exception\TransformerException;
use W3rOne\JsonSchemaBundle\Transformer\AbstractTransformer;
use Symfony\Component\Form\FormInterface;

class Resolver
{
    private string $defaultLayout;

    public array $transformers = [];

    public function __construct(string $defaultLayout)
    {
        $this->defaultLayout = $defaultLayout;
    }

    public function addTransformer(AbstractTransformer $transformer, string $formType): void
    {
        if (!array_key_exists($formType, $this->transformers)) {
            $this->transformers[$formType] = $transformer;
        }
    }

    public function resolve(FormInterface $form): AbstractTransformer
    {
        $formTypes = [
            Utils::getFormType(get_class($form->getConfig()->getType()->getInnerType())),
            Utils::getFormType($form->getConfig()->getType()->getInnerType()->getParent() ?? '')
        ];

        foreach($formTypes as $formType) {
            if (isset($this->transformers[$formType])) {
                return $this->transformers[$formType];
            }
        }

        if (true === $form->getConfig()->getOption('compound', false)) {
            return $this->transformers['object'];
        }

        throw new TransformerException(sprintf('Unable to find a transformer for type `%s`.', get_class($form->getConfig()->getType()->getInnerType())));
    }

    public function getDefaultLayout(): string
    {
        return $this->defaultLayout;
    }
}
