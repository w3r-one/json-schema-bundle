<?php

/*
 * This file is part of the W3rOneJsonSchemaBundle.
 *
 * (c) w3r-one <contact@w3r.one>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace W3rOne\JsonSchemaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class W3rOneJsonSchemaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('w3r_one_json_schema.default_layout', $config['default_layout']);
        $container->setParameter('w3r_one_json_schema.transformer_namespaces', $config['transformer_namespaces']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
