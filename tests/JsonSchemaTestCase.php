<?php

/*
 * This file is part of the W3rOneJsonSchemaBundle.
 *
 * (c) w3r-one <contact@w3r.one>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3rOne\JsonSchemaBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use W3rOne\JsonSchemaBundle\Form\Extension\JsonSchemaExtension;
use W3rOne\JsonSchemaBundle\JsonSchema;
use W3rOne\JsonSchemaBundle\Resolver;

class JsonSchemaTestCase extends TestCase
{
    protected static $defaultLayout = 'default';

    /** @var JsonSchema */
    protected $jsonSchema;

    /** @var FormFactoryInterface */
    protected $factory;

    /** @var array */
    protected $schema = [];

    protected function setUp(): void
    {
        parent::setUp();

        $this->jsonSchema = new JsonSchema(new Resolver(
            $this->getMockBuilder(RequestStack::class)->getMock(),
            $this->getMockBuilder(CsrfTokenManagerInterface::class)->getMock(),
            $this->getMockBuilder(TranslatorInterface::class)->getMock(),
            self::$defaultLayout,
            []
        ));
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions([])
            ->addTypeExtensions([new JsonSchemaExtension()])
            ->getFormFactory();
    }

    protected function getCombinations(array $possibleParts): array
    {
        $possibleCombinations = [[]];

        foreach ($possibleParts as $part) {
            foreach ($possibleCombinations as $combination) {
                array_push($possibleCombinations, array_merge(array($part), $combination));
            }
        }

        return array_filter($possibleCombinations);
    }
}
