<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

chdir(__DIR__);

require 'vendor/autoload.php';

$configData = include 'config/config.php';

$config    = new SlimApi\Kernel\Config();
$container = new SlimApi\Kernel\Container();

$container
    ->setConfig($config->setConfig($configData))
    ->getModule()
    ->run();