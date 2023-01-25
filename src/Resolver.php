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
use W3rOne\JsonSchemaBundle\Transformer\ObjectTransformer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class Resolver
{
    private $requestStack;

    private $csrfTokenManager;

    private $translator;

    private $defaultLayout;

    private $transformerNamespaces;

    public function __construct(RequestStack $requestStack, CsrfTokenManagerInterface $csrfTokenManager, TranslatorInterface $translator, string $defaultLayout, array $transformerNamespaces)
    {
        $this->requestStack = $requestStack;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->translator = $translator;
        $this->defaultLayout = $defaultLayout;
        $this->transformerNamespaces = $transformerNamespaces;
        $this->transformerNamespaces[] = __NAMESPACE__ . '\\Transformer\\Type\\';
    }

    public function resolve(FormInterface $form): AbstractTransformer
    {
        $fqnFormType = get_class($form->getConfig()->getType()->getInnerType());
        $fqnParentFormType = $form->getConfig()->getType()->getInnerType()->getParent();

        foreach($this->transformerNamespaces as $namespace) {
            if (class_exists($formTypeTransformer = $namespace . Utils::substrAfterLastDelimiter($fqnFormType, '\\') . 'Transformer')) {
                return new $formTypeTransformer($this->requestStack, $this->csrfTokenManager, $this->translator, $this);
            } elseif (class_exists($formTypeTransformer = $namespace . Utils::substrAfterLastDelimiter($fqnParentFormType, '\\') . 'Transformer')) {
                return new $formTypeTransformer($this->requestStack, $this->csrfTokenManager, $this->translator, $this);
            }
        }
        if (true === $form->getConfig()->getOption('compound', false)) {
            return new ObjectTransformer($this->requestStack, $this->csrfTokenManager, $this->translator, $this);
        }

        throw new TransformerException(sprintf('Unable to find a transformer for type `%s`.', $fqnFormType));
    }

    public function getDefaultLayout(): string
    {
        return $this->defaultLayout;
    }
}
