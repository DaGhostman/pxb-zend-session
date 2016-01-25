<?php
/**
 * Created by PhpStorm.
 * User: ddimitrov
 * Date: 24/01/16
 * Time: 14:21
 */

namespace PXB\Module\Zend\Session;

use Interop\Container\ContainerInterface;
use Zend\Expressive\ConfigManager\PhpFileProvider as ConfigProvider;

class SessionModuleConfig
{

    /**
     * Returns a configuration container, that contains configurations
     * applicable to this module (templates, routes, dependencies, etc)
     *
     * @param ContainerInterface $container
     * @return ConfigProvider
     *
     * @codeCoverageIgnore
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ConfigLoader('../config/{{,*.}global,{,*.}local}.php');
    }
}
