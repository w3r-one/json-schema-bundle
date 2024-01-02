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
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use W3rOne\JsonSchemaBundle\Form\Extension\JsonSchemaExtension;
use W3rOne\JsonSchemaBundle\JsonSchema;
use W3rOne\JsonSchemaBundle\Resolver;
use W3rOne\JsonSchemaBundle\Transformer\ObjectTransformer;
use W3rOne\JsonSchemaBundle\Utils;

class JsonSchemaTestCase extends TestCase
{
    protected static string $defaultLayout = 'default';

    protected JsonSchema $jsonSchema;

    protected FormFactoryInterface $factory;

    protected array $schema = [];

    protected function setUp(): void
    {
        parent::setUp();

        $requestStack = $this->getMockBuilder(RequestStack::class)->getMock();
        $csrfTokenManager = $this->getMockBuilder(CsrfTokenManagerInterface::class)->getMock();
        $translator = $this->getMockBuilder(TranslatorInterface::class)->getMock();

        $resolver = new Resolver(self::$defaultLayout);
        $resolver->addTransformer(new ObjectTransformer($requestStack, $csrfTokenManager, $translator, $resolver), 'object');
        $finder = new Finder();
        foreach($finder->files()->in(__DIR__ . '/../src/Transformer/Type')->name('*.php') as $file) {
            $fqn = 'W3rOne\\JsonSchemaBundle\\Transformer\\Type\\' . Utils::substrBeforeLastDelimiter($file->getFilename(), '.php');
            $resolver->addTransformer(new $fqn($requestStack, $csrfTokenManager, $translator, $resolver), Utils::getFormType($fqn));
        }

        $this->jsonSchema = new JsonSchema($resolver);

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
