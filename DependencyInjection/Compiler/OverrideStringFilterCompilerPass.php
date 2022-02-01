<?php

namespace Lpweb\SonataAdminPostgreSQLCaseInsensitiveLikeBundle\DependencyInjection\Compiler;


use Lpweb\SonataAdminPostgreSQLCaseInsensitiveLikeBundle\Filter\CaseInsensitiveStringFilter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class OverrideStringFilterCompilerPass implements CompilerPassInterface {

    /**
     * @inheritdoc
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container) {
        try {
            // Overrides sonata.admin.orm.filter.type.string's class
            $definition = $container->getDefinition('sonata.admin.orm.filter.type.string');
            $definition->setClass(CaseInsensitiveStringFilter::class);
        } catch (ServiceNotFoundException $e) {
            // Do nothing
        }
    }

}