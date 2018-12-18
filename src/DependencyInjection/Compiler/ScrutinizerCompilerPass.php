<?php

namespace App\DependencyInjection\Compiler;

use App\Scrutinizer\ScrutinizerChain;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * ScrutinizerCompilerPass
 *
 * @author Magnus Reiß <info@magnus-reiss.de>
 */
class ScrutinizerCompilerPass implements CompilerPassInterface
{
    const SERVICE_TAG_NAME     = 'uply.scrutinizer';
    const CHAIN_SERVICE_ID     = ScrutinizerChain::class;
    const METHOD_ADD_TO_CHAIN  = 'addScrutinizer';
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
            $chainDefinition->addMethodCall(
                self::METHOD_ADD_TO_CHAIN,
                [new Reference($service)]
            );
        }
    }
}