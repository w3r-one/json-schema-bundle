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

use Doctrine\Inflector\InflectorFactory;
use Symfony\Component\Form\FormInterface;

class Utils
{
    public static function getTranslationDomain(FormInterface $form, bool $forceParent = false): ?string
    {
        if (false === ($translationDomain = $form->getConfig()->getOption('translation_domain'))) {
            return null;
        }
        elseif (false === $forceParent && null !== $translationDomain) {
            return $translationDomain;
        }
        elseif (null !== $form->getParent()) {
            return self::getTranslationDomain($form->getParent());
        }

        return null;
    }

    public static function getWidget(FormInterface $form): string
    {
        return InflectorFactory::create()->build()->tableize(Utils::substrBeforeFirstDelimiter(Utils::substrAfterLastDelimiter(get_class($form->getConfig()->getType()->getInnerType()), '\\'), 'Type'));
    }

    public static function getErrors(FormInterface $form, $errors = []): array
    {
        if (null === $form->getParent()) {
            foreach ($form->getErrors() as $error) {
                $errors[self::getFullPropertyPath($form)][] = $error->getMessage() . (method_exists($error->getCause(), 'getPropertyPath') && !empty($propertyPath = $error->getCause()->getPropertyPath() ?? null) ? ' (' . $propertyPath . ')' : '');
            }
        }

        foreach ($form->all() as $child) {
            foreach ($child->getErrors() as $error) {
                if (!array_key_exists(self::getFullPropertyPath($child), $errors) || !in_array($error->getMessage(), $errors[self::getFullPropertyPath($child)])) {
                    $errors[self::getFullPropertyPath($child)][] = $error->getMessage();
                }
            }
            if (is_iterable($child)) {
                $errors = self::getErrors($child, $errors);
            }
        }

        return $errors;
    }

    public static function getFullPropertyPath(FormInterface $form, string $path = ''): string
    {
        $path = $form->getPropertyPath() . (!empty($path) ? '.' . $path : '');

        // forms without any data_class returns an array (`[key]`) instead of a simple attribute (`property`)
        $path = trim($path, " \n\r\t\v\0[]");

        if (null !== $form->getParent()) {
            return self::getFullPropertyPath($form->getParent(), $path);
        }

        // replace user.languages.[1].speakingLevel to user[languages][1][speakingLevel]
        $propertyPath = str_replace([
                '.[',
                '].',
                '.',
            ], [
                '][',
                '][',
                '][',
            ], $path) . ']';

        return self::substrBeforeFirstDelimiter($propertyPath, ']') . self::substrAfterFirstDelimiter($propertyPath, ']');
    }

    public static function substrBeforeFirstDelimiter(string $haystack, string $delimiter): string
    {
        $exploded = explode($delimiter, $haystack);

        if (false === $exploded || 0 === \count($exploded)) {
            return $haystack;
        }

        return $exploded[0];
    }

    public static function substrBeforeLastDelimiter(string $haystack, string $delimiter): string
    {
        $exploded = explode($delimiter, $haystack);

        if (false === $exploded) {
            return $haystack;
        }

        return implode($delimiter, array_slice($exploded, 0, max(1, \count($exploded) - 1)));
    }

    public static function substrAfterLastDelimiter(string $haystack, string $delimiter): string
    {
        $exploded = explode($delimiter, $haystack);

        if (false === $exploded) {
            return $haystack;
        }

        return $exploded[\count($exploded) - 1];
    }

    public static function substrAfterFirstDelimiter(string $haystack, string $delimiter): string
    {
        $exploded = explode($delimiter, $haystack);

        if (false === $exploded) {
            return $haystack;
        }

        $count = \count($exploded);

        return implode($delimiter, array_slice($exploded, 1 === $count ? 0 : 1, max(1, $count - 1)));
    }

    public static function getFormType(string $fqnFormType): string
    {
        return mb_strtolower(preg_replace('~(?<=\\w)([A-Z])~u', '_$1', self::substrBeforeLastDelimiter(self::substrAfterLastDelimiter($fqnFormType, '\\'), 'Type')));
    }
}
