<?php

namespace Xaben\InstagramBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('xaben_instagram');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                ->integerNode('user_id')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('user_name')->isRequired()->cannotBeEmpty()->end()
                ->integerNode('limit')->defaultValue(6)->end()
                ->scalarNode('template')->defaultValue('XabenInstagramBundle:Block:instagram.html.twig')->end()
                ->scalarNode('cache_service')->isRequired()->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
