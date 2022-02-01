<?php

namespace Lpweb\SonataAdminPostgreSQLCaseInsensitiveLikeBundle;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Lpweb\SonataAdminPostgreSQLCaseInsensitiveLikeBundle\DependencyInjection\Compiler\OverrideStringFilterCompilerPass;

class LpwebSonataAdminPostgreSQLCaseInsensitiveLikeBundle extends Bundle {

    public function build(ContainerBuilder $container) {
        parent::build($container);

        $container->addCompilerPass(new OverrideStringFilterCompilerPass());
    }

}