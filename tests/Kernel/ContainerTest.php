<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use SlimApi\Kernel\Container;
use SlimApi\Kernel\Routing;
use SlimApi\Kernel\Module;
use SlimApi\Kernel\Config;
use Slim\Slim;

class ContainerTest extends PHPUnit_Framework_TestCase
{
    /** @var Container */
    private $sut;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Module */
    private $module;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Routing */
    private $routing;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Config */
    private $config;

    public function setUp()
    {
        $this->sut = new Container();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Routing
     */
    protected function getRoutingMock()
    {
        if (!$this->routing) {
            $methods = array('setContainer', 'setSlim');
            $this->routing = $this
                ->getMockBuilder('Api\Kernel\Routing')
                ->setMethods($methods)
                ->getMock();
        }

        return $this->routing;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Module
     */
    protected function getModuleMock()
    {
        if (!$this->module) {
            $methods = array('setRouting');
            $this->module = $this
                ->getMockBuilder('Api\Kernel\Module')
                ->setMethods($methods)
                ->getMock();
        }

        return $this->module;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Module
     */
    protected function getConfigMock()
    {
        if (!$this->config) {
            $methods = array('get');
            $this->config = $this
                ->getMockBuilder('Api\Kernel\Config')
                ->setMethods($methods)
                ->getMock();
        }

        return $this->config;
    }

    public function testSetConfig()
    {
        $config = $this->getConfigMock();
        $response = $this->sut->setConfig($config);
        $this->assertInstanceOf('Api\Kernel\Container', $response);
    }

    public function testGetConfig()
    {
        $config = $this->getConfigMock();
        $actual = $this->sut->setConfig($config)->getConfig();
        $this->assertInstanceOf('Api\Kernel\Config', $actual);
    }

    public function testInitializeRouting()
    {
        $this->getRoutingMock()
             ->expects($this->once())
             ->method('setContainer')
             ->with($this->isInstanceOf('\Api\Kernel\Container'))
             ->will($this->returnSelf());

        $this->getRoutingMock()
             ->expects($this->once())
             ->method('setSlim')
             ->with($this->isInstanceOf('\Slim\Slim'))
             ->will($this->returnSelf());

        $this->getConfigMock()
             ->expects($this->any())
             ->method('get')
             ->with($this->equalTo(Config::ROUTING))
             ->will($this->returnValue($this->getRoutingMock()));

        /** @var \PHPUnit_Framework_MockObject_MockObject|Routing $routing */
        $routing = $this->sut->setConfig($this->getConfigMock())->get(Container::ROUTING);
        $this->assertEquals(get_class($this->getRoutingMock()), get_class($routing));
    }

    public function testInitializeModule()
    {
        $this->getRoutingMock()
             ->expects($this->once())
             ->method('setContainer')
             ->will($this->returnSelf());

        $this->getRoutingMock()
             ->expects($this->once())
             ->method('setSlim')
             ->will($this->returnSelf());

        $this->getModuleMock()
             ->expects($this->once())
             ->method('setRouting')
             ->with($this->isInstanceOf(get_class($this->getRoutingMock())))
             ->will($this->returnSelf());

        $this->getConfigMock()
             ->expects($this->at(0))
             ->method('get')
             ->with($this->equalTo(Config::MODULE))
             ->will($this->returnValue($this->getModuleMock()));

        $this->getConfigMock()
             ->expects($this->at(1))
             ->method('get')
             ->with($this->equalTo(Config::ROUTING))
             ->will($this->returnValue($this->getRoutingMock()));

        /** @var \PHPUnit_Framework_MockObject_MockObject|Routing $module */
        $module = $this->sut->setConfig($this->getConfigMock())->getModule();
        $this->assertEquals(get_class($this->getModuleMock()), get_class($module));
    }
}