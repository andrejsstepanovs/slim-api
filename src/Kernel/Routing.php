<?php

namespace SlimApi\Kernel;

use Slim\Slim;

/**
 * Class Routing
 *
 * @package SlimApi\Kernel
 */
class Routing
{
    /** @var Slim */
    private $slim;

    /** @var Container */
    private $container;

    /**
     * @param Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param Slim $app
     *
     * @return $this
     */
    public function setSlim(Slim $app)
    {
        $this->slim = $app;

        return $this;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return Slim
     */
    public function getSlim()
    {
        return $this->slim;
    }

    /**
     * Override this method to set up routing.
     * Use $this->getSlim()
     * http://docs.slimframework.com/#Routing-Overview
     *
     * @return $this
     */
    public function init()
    {
        return $this;
    }
}