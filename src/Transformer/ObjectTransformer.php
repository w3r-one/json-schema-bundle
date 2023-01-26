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
use Symfony\Component\HttpFoundation\Request;
use W3rOne\JsonSchemaBundle\Utils;

class ObjectTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form): array
    {
        // nested compound object
        if (null !== $form->getParent()) {
            $schema = parent::transform($form);
            $schema['properties'] = [];
            $schema['type'] = 'object';
        }
        // base json schema
        else {
            if (null !== ($request = $this->requestStack->getCurrentRequest())) {
                $hostName = $request->getSchemeAndHttpHost();
                if (empty($action = $form->getConfig()->getOption('action'))) {
                    $action = $request->getUri();
                }
                elseif (0 === strpos(strtolower(trim($action)), 'http')) {
                    $action = $request->getUriForPath($action);
                }
            }
            else {
                $hostName = $action = 'http://localhost';
            }
            $widget = Utils::getWidget($form);

            $schema = [
                '$schema' => 'https://json-schema.org/draft/2020-12/schema',
                # TODO: perhaps find a way to use more specific $id for caching purpose
                '$id' => $hostName . '/schemas/' . $form->getName() . '.json',
                'type' => 'object',
                'title' => $form->getName(),
                'properties' => [],
                # TODO: try to guess required props
                'required' => [],
                'options' => [
                    'widget' => !empty($widget) ? $widget : 'object',
                    'layout' => $form->getConfig()->getOption('w3r_one_json_schema')['layout'] ?? $this->resolver->getDefaultLayout(),
                    'form' => [
                        'method' => $form->getConfig()->getOption('method', Request::METHOD_POST),
                        'action' => $action,
                        'async' => $form->getConfig()->getOption('w3r_one_json_schema')['xmlHttpRequest'] ?? true,
                    ],
                ],
            ];

            // add csrf field if needed
            if (true === $form->getConfig()->getOption('csrf_protection')) {
                $schema['properties'][$form->getConfig()->getOption('csrf_field_name', '_token')] = [
                    'type' => 'string',
                    'title' => '',
                    'writeOnly' => true,
                    'default' => $this->csrfTokenManager->getToken($form->getName())->getValue(),
                    'options' => [
                        'widget' => 'hidden',
                        'layout' => $this->resolver->getDefaultLayout(),
                    ],
                ];
            }
        }

        return $schema;
    }
}
