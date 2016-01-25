<?php
/**
 * Created by PhpStorm.
 * User: ddimitrov
 * Date: 24/01/16
 * Time: 14:35
 */

namespace PXB\Module\Zend\Session\Factory;

use Interop\Container\ContainerInterface;
use Zend\Session\Config\SessionConfig;

class ConfigFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $options = $container->get('config')['session']['options'];

        $config = new SessionConfig();
        $config->setOptions($options);

        return $config;
    }
}
