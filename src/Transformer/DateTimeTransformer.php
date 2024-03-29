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

class DateTimeTransformer extends ObjectTransformer
{
    public function transform(FormInterface $form): array
    {
        if ('single_text' === ($widget = $form->getConfig()->getOption('widget', 'choice'))) {
            $schema = (new StringTransformer($this->requestStack, $this->csrfTokenManager, $this->translator, $this->resolver))->transform($form);
        }
        else {
            $schema = parent::transform($form);
            if (null !== $form->getConfig()->getOption('format')) {
                $schema['options']['date_time']['format'] = $form->getConfig()->getOption('format', \IntlDateFormatter::MEDIUM);
            }
            if (null !== $form->getConfig()->getOption('input')) {
                $schema['options']['date_time']['input'] = $form->getConfig()->getOption('input', 'datetime');
            }
            if (null !== $form->getConfig()->getOption('input_format')) {
                $schema['options']['date_time']['inputFormat'] = $form->getConfig()->getOption('input_format', 'Y-m-d');
            }
            if (null !== $form->getConfig()->getOption('model_timezone')) {
                $schema['options']['date_time']['modelTimezone'] = null !== ($modelTimezone = $form->getConfig()->getOption('model_timezone')) ? $modelTimezone : date_default_timezone_get();
            }
            if (null !== ($placeholder = $form->getConfig()->getOption('placeholder'))) {
                if (is_array($placeholder)) {
                    foreach($placeholder as $key => $value) {
                        $schema['options']['date_time']['placeholder'][$key] = $this->translate($form, $value, $form->getConfig()->getOption('choice_translation_parameters', []));
                    }
                }
                else {
                    $schema['options']['date_time']['placeholder'] = $this->translate($form, $placeholder, $form->getConfig()->getOption('choice_translation_parameters', []));
                }
            }
        }
        if (null === ($form->getConfig()->getOption('w3r_one_json_schema')['widget'] ?? null)) {
            $schema['options']['widget'] = $schema['options']['widget'] . '_' . $widget;
        }

        return $schema;
    }
}
