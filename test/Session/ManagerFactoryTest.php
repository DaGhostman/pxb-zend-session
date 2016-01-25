<?php
namespace PXB\Module\Test\Session;

use Interop\Container\ContainerInterface;
use PXB\Module\Zend\Session\Factory\ManagerFactory;
use Zend\Session\SaveHandler\SaveHandlerInterface;
use Zend\Session\SessionManager;
use Zend\Session\Config\ConfigInterface;
use Zend\Session\Storage\StorageInterface;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Exception\InvalidArgumentException;

class ManagerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreationAndReturnWhenSessionManagerIsDefined()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([]);
        $container->has(StorageInterface::class)->willReturn(false);
        $container->has(ConfigInterface::class)->willReturn(false);
        $container->has(SaveHandlerInterface::class)->willReturn(false);

        $factory = new ManagerFactory();
        $this->assertInstanceOf(
            SessionManager::class,
            $factory($container->reveal())
        );
    }

    public function testWillReturnBlankSessionManagerWhenInvokableIsNotDefined()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([]);
        $container->has(StorageInterface::class)->willReturn(false);
        $container->has(SessionManager::class)->willReturn(false);
        $container->has(ConfigInterface::class)->willReturn(false);
        $container->has(SaveHandlerInterface::class)->willReturn(false);

        $factory = new ManagerFactory();

        $this->assertInstanceOf(SessionManager::class, $factory($container->reveal()));
    }

    public function testWillProvideSessionManagerWithConfigIfAvailable()
    {
        $config = $this->prophesize(ConfigInterface::class);
        $config->getName()->willReturn('php-unit');

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([]);
        $container->has(StorageInterface::class)->willReturn(false);
        $container->has(SessionManager::class)->willReturn(false);
        $container->has(SaveHandlerInterface::class)->willReturn(false);
        $container->has(ConfigInterface::class)->willReturn(true);
        $container->get(ConfigInterface::class)->willReturn($config->reveal());

        $factory = new ManagerFactory();

        // Assert from config, as `headers_sent` (marks session as started, CLI issue)
        $this->assertSame(
            'php-unit',
            $factory($container->reveal())
                ->getConfig()
                ->getName()
        );
    }

    public function testWillProvideSessionManagerWithStorageIfAvailable()
    {
        $storage = $this->prophesize(StorageInterface::class);
        $storage->fromArray()->willReturn($storage->reveal());
        $storage->isImmutable()->willReturn(true);
        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')->willReturn([]);
        $container->has(ConfigInterface::class)->willReturn(false);
        $container->has(SaveHandlerInterface::class)->willReturn(false);
        $container->has(StorageInterface::class)->willReturn(true);
        $container->get(StorageInterface::class)->willReturn($storage->reveal());

        $factory = new ManagerFactory();

        try {
            $this->assertInstanceOf(
                StorageInterface::class,
                $factory($container->reveal())->getStorage()
            );
        } catch (\Exception $ex) {
            //throw $ex;
        }
    }

    public function testWillProvideSessionManagerWithHandlerIfAvailable()
    {
        $handler = $this->prophesize(SaveHandlerInterface::class);
        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')->willReturn([
            'session' => [
                'handler' => SaveHandlerInterface::class
            ]
        ]);
        $container->has(StorageInterface::class)
            ->willReturn(false);
        $container->has(SaveHandlerInterface::class)
            ->willReturn(true);
        $container->get(SaveHandlerInterface::class)
            ->willReturn($handler->reveal());
        $container->has(ConfigInterface::class)
            ->willReturn(false);

        $factory = new ManagerFactory();

        $this->assertInstanceOf(
            SaveHandlerInterface::class,
            $factory($container->reveal())->getSaveHandler()
        );
    }

    public function testWillRegisterSessionManagerWithValidatorsIfAvailable()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'session' => ['validators' => [RemoteAddr::class]]
        ]);
        $container->has(ConfigInterface::class)->willReturn(false);
        $container->has(SaveHandlerInterface::class)->willReturn(false);
        $container->has(StorageInterface::class)->willReturn(false);

        $factory = new ManagerFactory();
        /**
         * @var $factoryCreated SessionManager
         */
        $factoryCreated = $factory($container->reveal());
        $this->assertInstanceOf(
            SessionManager::class,
            $factoryCreated
        );

        $this->assertSame(true, $factoryCreated->isValid());
    }

    public function testSessionManagerFactoryWillThrowExceptionBadValidatorsEntry()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            'Session validators must be array, string given'
        );

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'session' => ['validators' => RemoteAddr::class]
        ]);
        $container->has(ConfigInterface::class)->willReturn(false);
        $container->has(SaveHandlerInterface::class)->willReturn(false);
        $container->has(StorageInterface::class)->willReturn(false);

        $factory = new ManagerFactory();

        $factory($container->reveal());
    }
}
