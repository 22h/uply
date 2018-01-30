<?php

namespace App\DependencyInjection\Compiler;

use App\Monitor\MonitorUnitChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * MonitorUnitCompilerPass
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class MonitorUnitCompilerPass implements CompilerPassInterface
{
    const SERVICE_TAG_NAME     = 'uply.monitor.unit';
    const CHAIN_SERVICE_ID     = MonitorUnitChain::class;
    const METHOD_ADD_TO_CHAIN  = 'addMonitorUnit';
    const ATTRIBUTE_IDENTIFIER = 'identifier';

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(self::CHAIN_SERVICE_ID)) {
            return;
        }

        $chainDefinition = $container->findDefinition(self::CHAIN_SERVICE_ID);
        $factoryDefinitions = $container->findTaggedServiceIds(self::SERVICE_TAG_NAME);

        foreach ($factoryDefinitions as $service => $tags) {
            foreach ($tags as $attributes) {
                $chainDefinition->addMethodCall(
                    self::METHOD_ADD_TO_CHAIN,
                    [new Reference($service), $attributes[self::ATTRIBUTE_IDENTIFIER]]
                );
            }
        }
    }
}
