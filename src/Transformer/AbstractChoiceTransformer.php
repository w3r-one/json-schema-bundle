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

use W3rOne\JsonSchemaBundle\Utils;
use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;
use Symfony\Component\Form\FormInterface;

class AbstractChoiceTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form): array
    {
        $schema = parent::transform($form);
        $schema['options']['choice'] = [
            'expanded' => $form->getConfig()->getOption('expanded', false),
            'multiple' => $form->getConfig()->getOption('multiple', false),
            'filterable' => $form->getConfig()->getOption('w3r_one_json_schema')['filterable'] ?? true,
        ];
        if (!empty($placeholder = $form->getConfig()->getOption('placeholder'))) {
            $schema['options']['choice']['placeholder'] = $placeholder;
        }
        if (!empty($preferredChoices = $form->getConfig()->getOption('preferred_choices'))) {
            $schema['options']['choice']['preferredChoices'] = $preferredChoices;
        }

        $enums = $enumTitles = [];
        $formView = $form->createView();
        foreach ($formView->vars['choices'] as $choiceView) {
            if ($choiceView instanceof ChoiceGroupView) {
                foreach ($choiceView->choices as $choiceItem) {
                    $enums[] = $choiceItem->value;
                    $enumTitles[] = $this->translator->trans($choiceItem->label, $choiceView->labelTranslationParameters ?? [], Utils::getTranslationDomain($form));
                }
            } else {
                $enums[] = $choiceView->value;
                $enumTitles[] = $this->translator->trans($choiceView->label, $choiceView->labelTranslationParameters ?? [], Utils::getTranslationDomain($form));
            }
        }

        if (true === $form->getConfig()->getOption('multiple', false)) {
            $schema = array_merge($schema, [
                'type' => 'array',
                'items' => [
                    'type' => 'string',
                    'enum' => $enums,
                ],
                'minItems' => true === $form->getConfig()->getOption('required', false) ? 1 : 0,
                'uniqueItems' => true,
            ]);
        } else {
            $schema = array_merge($schema, [
                'type' => 'string',
                'enum' => $enums,
            ]);
        }
        $schema['options']['choice']['enumTitles'] = $enumTitles;

        return $schema;
    }
}
