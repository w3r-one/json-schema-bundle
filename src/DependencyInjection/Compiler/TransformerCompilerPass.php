<?php

/*
 * This file is part of the W3rOneJsonSchemaBundle.
 *
 * (c) w3r-one <contact@w3r.one>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3rOne\JsonSchemaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use W3rOne\JsonSchemaBundle\Transformer\TransformerInterface;

class TransformerCompilerPass implements CompilerPassInterface
{
    const TRANSFORMER_TAG = 'w3r_one_json_schema.transformer';

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container)
    {
        $resolver = $container->getDefinition('W3rOne\JsonSchemaBundle\Resolver');

        foreach ($container->findTaggedServiceIds(self::TRANSFORMER_TAG) as $id => $attributes) {
            $transformer = $container->getDefinition($id);

            if (!isset(class_implements($transformer->getClass())[TransformerInterface::class])) {
                throw new \InvalidArgumentException(sprintf(
                    "The service %s was tagged as a '%s' but does not implement the mandatory %s",
                    $id,
                    self::TRANSFORMER_TAG,
                    TransformerInterface::class
                ));
            }

            foreach ($attributes as $attribute) {
                if (!isset($attribute['form_type'])) {
                    throw new \InvalidArgumentException(sprintf(
                        "The service %s was tagged as a '%s' but does not specify the mandatory 'form_type' option.",
                        $id,
                        self::TRANSFORMER_TAG
                    ));
                }

                $resolver->addMethodCall('addTransformer', [new Reference($id), $attribute['form_type']]);
            }
        }
    }
}
