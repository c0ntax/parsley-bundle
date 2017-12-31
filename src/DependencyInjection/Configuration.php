<?php

namespace C0ntax\ParsleyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('c0ntax_parsley');

        // @formatter:off
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enabled')->defaultFalse()->info('Turn attr injection on or off')->end()
                ->arrayNode('field')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('trigger')->defaultNull()->info('The jquery event type on a field that will cause parsley to validate (if you don\'t want to leave it to the submit button')->end()
                    ->end()
                ->end()
            ->end()
        ;
        // @formatter:on

        return $treeBuilder;
    }
}
