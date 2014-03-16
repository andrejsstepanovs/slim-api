<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use SlimApi\Kernel\Module;
use SlimApi\Kernel\Routing;

class ModuleTest extends PHPUnit_Framework_TestCase
{
    /** @var Module */
    private $sut;

    /** @var \PHPUnit_Framework_MockObject_MockObject|Routing */
    private $routing;

    public function setUp()
    {
        $this->sut = new Module();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Routing
     */
    protected function getRoutingMock()
    {
        if (!$this->routing) {
            $methods = array('init', 'getSlim', 'run');
            $this->routing = $this
                ->getMockBuilder('SlimApi\Kernel\Routing')
                ->setMethods($methods)
                ->getMock();
        }

        return $this->routing;
    }

    public function testRun()
    {
        $this->getRoutingMock()->expects($this->once())->method('init')->will($this->returnSelf());
        $this->getRoutingMock()->expects($this->once())->method('getSlim')->will($this->returnSelf());
        $this->getRoutingMock()->expects($this->once())->method('run')->will($this->returnSelf());

        $this->sut->setRouting($this->getRoutingMock())->run();
    }
}