<?php

namespace PXB\Module\Zend\Session;

use Zend\Expressive\ConfigManager\PhpFileProvider as ConfigProvider;

class SessionModuleConfig
{
    /**
     * Returns a configuration container, that contains configurations
     * applicable to this module (templates, routes, dependencies, etc)
     *
     * @return ConfigProvider
     *
     * @codeCoverageIgnore
     */
    public function __invoke()
    {
        return new ConfigProvider(__DIR__ . '/../config/{{,*.}global,{,*.}local}.php');
    }
}
