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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('w3r_one_json_schema');
        $rootNode = (method_exists($treeBuilder, 'getRootNode')) ? $treeBuilder->getRootNode() : $treeBuilder->root('w3r_one_json_schema');

        $rootNode
            ->children()
                ->scalarNode('default_layout')->info('The default layout applied to all your components.')->defaultValue('default')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
