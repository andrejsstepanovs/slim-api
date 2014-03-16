<?php

namespace SlimApi\Kernel;

use SlimApi\Kernel\Routing;

/**
 * Class Module
 *
 * @package SlimApi\Kernel
 */
class Module
{
    /** @var Routing */
    private $routing;

    /**
     * @param Routing $routing
     *
     * @return $this
     */
    public function setRouting(Routing $routing)
    {
        $this->routing = $routing;

        return $this;
    }

    /**
     * @return Routing
     */
    private function getRouting()
    {
        return $this->routing;
    }

    /**
     * Initialize application routing and run slim micro framework
     */
    public function run()
    {
        $this->getRouting()->init()->getSlim()->run();
    }
}