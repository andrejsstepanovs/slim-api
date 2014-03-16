<?php

namespace SlimApi\Kernel;

use Pimple;

/**
 * Class Container
 *
 * @package SlimApi\Kernel
 */
class Container extends Pimple
{
    /** routing container key */
    const ROUTING = 'routing';

    /** module container key */
    const MODULE = 'module';

    /** config container key */
    const CONFIG = 'config';

    /** @var bool */
    protected $isInitialized = false;

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        if (!$this->isInitialized) {
            $this->isInitialized = true;
            $this->initialize();
        }

        return $this[$name];
    }

    /**
     * @param Config $config
     *
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this[self::CONFIG] = $config;

        return $this;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this[self::CONFIG];
    }

    /**
     * @return void
     */
    protected function initialize()
    {
        $this->initRouting()->initModule();
    }

    /**
     * @return $this
     */
    protected function initRouting()
    {
        $this[self::ROUTING] = function () {
            /** @var Routing $routing */
            $routingClass = $this->getConfig()->get(Config::ROUTING);
            $routing = is_object($routingClass) ? $routingClass : new $routingClass;
            $routing->setContainer($this)->setSlim(new \Slim\Slim());
            return $routing;
        };

        return $this;
    }

    /**
     * @return $this
     */
    protected function initModule()
    {
        $this[self::MODULE] = function () {
            /** @var Module $module */
            $moduleClass = $this->getConfig()->get(Config::MODULE);
            $module = is_object($moduleClass) ? $moduleClass : new $moduleClass;
            $module->setRouting($this[self::ROUTING]);
            return $module;
        };

        return $this;
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        return $this->get(self::MODULE);
    }
}