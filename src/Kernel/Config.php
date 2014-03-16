<?php

namespace SlimApi\Kernel;

/**
 * Class Config
 *
 * @package SlimApi\Kernel
 */
class Config
{
    /** development mode config key value */
    const DEVMODE = 'devmode';

    /** routing class config key value */
    const ROUTING = 'routing';

    /** module class config key value */
    const MODULE = 'module';

    /** container class config key value */
    const CONTAINER = 'container';

    /** @var array */
    private $config = array(
        Config::MODULE    => 'SlimApi\Kernel\Module',
        Config::ROUTING   => 'SlimApi\Kernel\Routing'
    );

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool|string|array
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->config)) {
            throw new \InvalidArgumentException('Config with name "' . $name . '" not found');
        }

        return $this->config[$name];
    }
}