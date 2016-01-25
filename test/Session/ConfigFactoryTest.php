<?php
namespace PXB\Module\Test\Session;

use Interop\Container\ContainerInterface;
use PXB\Module\Zend\Session\Factory\ConfigFactory;
use Zend\Session\Config\ConfigInterface;
use Zend\Session\Config\SessionConfig;

class ConfigFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testConfigCreationWhenEmpty()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'session' => [
                'options' => [
                    'name' => 'php-unit'
                ]
            ]
        ]);
        $factory = new ConfigFactory();
        $factoryConstructed = $factory($container->reveal());

        $this->assertInstanceOf(ConfigInterface::class, $factoryConstructed);
        $this->assertInstanceOf(SessionConfig::class, $factoryConstructed);

        $this->assertSame('php-unit', $factoryConstructed->getName());
    }
}
