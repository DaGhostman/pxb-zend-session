<?php

use \PXB\Module\Zend\Session;

/**
 * @ToDo: Add \Zend\Session\SaveHandler\SaveHandlerInterface factory to add as session storage
 */
return [
    'dependencies' => [
        'invokables' => [
            \Zend\Session\Storage\StorageInterface::class => \Zend\Session\Storage\SessionArrayStorage::class
        ],
        'factories' => [
            \Zend\Session\Config\ConfigInterface::class => Session\Factory\ConfigFactory::class,
            \Zend\Session\SessionManager::class => Session\Factory\ManagerFactory::class,
            \PXB\Module\Zend\Session\EventHandler\SessionEventHandler::class =>
                PXB\Module\Zend\Session\Factory\SessionEventHandlerFactory::class
        ],
    ]
];
