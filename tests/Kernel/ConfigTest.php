<?php

namespace Tests;

use PHPUnit_Framework_TestCase;
use SlimApi\Kernel\Config;

/**
 * Class ConfigTest
 *
 * @package Tests
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
    /** @var Config */
    private $sut;

    public function setUp()
    {
        $this->sut = new Config();
    }

    public function testSetConfig()
    {
        $config = array(
            Config::MODULE  => 'module',
            Config::ROUTING => 'routing',
            Config::SLIM    => 'slim'
        );
        $actual = $this->sut->setConfig($config)->getConfig();
        $this->assertEquals($config, $actual);
    }

    public function testGetExistingConfigKey()
    {
        $key = 'apple';
        $config = array(
            $key     => 'green',
            'banana' => 'yellow'
        );

        $actual = $this->sut->setConfig($config)->get($key);
        $this->assertEquals($config[$key], $actual);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetNotExistingConfigKey()
    {
        $config = array();
        $this->sut->setConfig($config)->get('unknown');
    }
}