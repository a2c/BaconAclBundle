<?php

namespace Bacon\Bundle\AclBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bacon_acl');

        $rootNode
            ->children()
                ->arrayNode('configuration')
                    ->children()
                        ->arrayNode('entities')
                            ->children()
                                ->scalarNode('module_class')->isRequired(true)->end()
                                ->scalarNode('module_actions')->isRequired(true)->end()
                                ->scalarNode('module_actions_group')->isRequired(true)->end()
                            ->end()
                        ->end()
                    ->end()
                    ->children()
                        ->arrayNode('forms')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('module_actions_form_type')->defaultValue('Bacon\\Bundle\\AclBundle\\Form\\Type\\ModuleActionsFormType')->isRequired(true)->end()
                                ->scalarNode('module_actions_form_handler')->defaultValue('Bacon\\Bundle\\AclBundle\\Form\\Handler\\ModuleActionsFormHandler')->isRequired(true)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;


        $rootNode
            ->children()
                ->scalarNode('route_redirect_after_save')->defaultValue('fos_user_group_list')->end()
                ->scalarNode('user_class')->isRequired(true)->end()
                ->scalarNode('group_class')->isRequired(true)->end()
            ->end()
        ->end()
        ;

        return $treeBuilder;
    }
}
