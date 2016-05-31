<?php

namespace Bacon\Bundle\AclBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BaconAclExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('bacon_acl.user_class', $config['user_class']);
        $container->setParameter('bacon_acl.group_class', $config['group_class']);
        $container->setParameter('bacon_acl.route_redirect_after_save', $config['route_redirect_after_save']);

        // Entities
        $container->setParameter('bacon_acl.entities.module_class', $config['configuration']['entities']['module_class']);
        $container->setParameter('bacon_acl.entities.module_actions', $config['configuration']['entities']['module_actions']);
        $container->setParameter('bacon_acl.entities.module_actions_group', $config['configuration']['entities']['module_actions_group']);

        // Forms
        $container->setParameter('bacon_acl.forms.module_actions_form_type_class', $config['configuration']['forms']['module_actions_form_type']);
        $container->setParameter('bacon_acl.forms.module_actions_form_handler_class', $config['configuration']['forms']['module_actions_form_handler']);


        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
