<?php

namespace App\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * AppExtension
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class AppExtension extends Extension
{

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter(
            'uply.monitoring_units',
            $config['monitoring_units']
        );

        $container->setParameter(
            'uply.notifications',
            $config['notifications']
        );
    }

    public function getAlias()
    {
        return 'uply';
    }


}
