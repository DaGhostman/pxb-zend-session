<?php
/**
 * Created by PhpStorm.
 * User: ddimitrov
 * Date: 23/01/16
 * Time: 19:23
 */

namespace PXB\Module\Zend\Session\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;
use Zend\Session\Config\ConfigInterface;
use Zend\Session\Exception\InvalidArgumentException;
use Zend\Session\Exception\RuntimeException;
use Zend\Session\SaveHandler\SaveHandlerInterface;
use Zend\Session\SessionManager;
use Zend\Session\Storage\StorageInterface;

class ManagerFactory
{
    protected $storage;

    /**
     * @param ContainerInterface $container
     * @return SessionManager
     *
     * @throws RuntimeException
     * @throws ContainerException
     * @throws InvalidArgumentException
     */
    public function __invoke(ContainerInterface $container)
    {
        $c = $container->get('config');
        $config = array_key_exists('session', $c) ? $c['session'] : ['name' => 'expressive'];

        $sessionConfig = null;
        $sessionSaveHandler = null;
        $sessionStorage = null;
        $sessionValidators = [];

        if ($container->has(ConfigInterface::class)) {
            $sessionConfig = $container->get(ConfigInterface::class);
        }
        if (array_key_exists('handler', $config)) {
            if ($container->has($config['handler'])) {
                $sessionSaveHandler = $container->get(SaveHandlerInterface::class);
            }
        }
        if ($container->has(StorageInterface::class)) {
            $sessionStorage = $container->get(StorageInterface::class);
        }
        if (array_key_exists('validators', $config)) {
            if (!is_array($config['validators'])) {
                throw new InvalidArgumentException(
                    'Session validators must be array, ' . gettype($config['validators']) . ' given'
                );
            }

            $sessionValidators = $config['validators'];
        }


        return new SessionManager(
            $sessionConfig,
            $sessionStorage,
            $sessionSaveHandler,
            $sessionValidators
        );
    }
}
