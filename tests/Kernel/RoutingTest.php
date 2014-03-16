<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use Api\Kernel\Routing;
use Api\Kernel\Container;
use Slim\Slim;

class RoutingTest extends PHPUnit_Framework_TestCase
{
    /** @var Routing */
    private $sut;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Container */
    private $container;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Slim */
    private $slim;

    public function setUp()
    {
        $this->sut = new Routing();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Container
     */
    protected function getContainerMock()
    {
        if (!$this->container) {
            $methods = array();
            $this->container = $this
                ->getMockBuilder('Api\Kernel\Container')
                ->disableOriginalConstructor()
                ->setMethods($methods)
                ->getMock();
        }

        return $this->container;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Slim
     */
    protected function getSlimMock()
    {
        if (!$this->slim) {
            $methods = array();
            $this->slim = $this
                ->getMockBuilder('Slim\Slim')
                ->disableOriginalConstructor()
                ->setMethods($methods)
                ->getMock();
        }

        return $this->slim;
    }

    public function testSetSlim()
    {
        $actual = $this->sut->setSlim($this->getSlimMock())->getSlim();
        $this->assertInstanceOf('Slim\Slim', $actual);
    }

    public function testSetContainer()
    {
        $actual = $this->sut->setContainer($this->getContainerMock())->getContainer();
        $this->assertInstanceOf('Api\Kernel\Container', $actual);
    }

    public function testInitReturnsSelf()
    {
        $actual = $this->sut->init();
        $this->assertInstanceOf('Api\Kernel\Routing', $actual);
    }
}