SlimApi
=============

Api framework setup using [slim][1] micro framework and [pimple][2]

Install
-----

Create composer.json file
``` json
{
    "require": {
        "wormhit/slim-api": "dev-master"
    },
    "autoload": {
        "psr-4": {"Api\\": "src/"}
    }
}
```

Download [composer][3] and run php composer.phar install

Setup
-----

After that edit your index.php file

``` php
<?php

require 'vendor/autoload.php';

$configData = array();
//$configData = array(Config::ROUTING => 'Api\Module\Routing');
//$configData = include 'config/config.php';

$config    = new SlimApi\Kernel\Config();
$container = new SlimApi\Kernel\Container();
$container
    ->setConfig($config->setConfig($configData))
    ->getModule()
    ->run();
```

Run
-----

Start application by running

``` sh
php -S localhost:8000
```

and point your browser to http://localhost:8080

Configure
-----

To create new routing path uncomment $configData = array(...) line in index.php and
creater new routing class

``` php
<?php

namespace Api\Module;
use SlimApi\Kernel\Routing as KernelRouting;

class Routing extends KernelRouting
{
    public function init()
    {
        $container = $this->getContainer();
        $slim = $this->getSlim();
        $slim->map(
             '/',
             function() use ($container, $slim) {
                 /** @var \Api\Controller\Index\IndexController $controller */
                 $controller = $container->get('controller.index.index');
                 $slim->response = $controller->getResponse();
             }
        )
        ->via('GET');
    }
}
```

Now your custome routing will match "/" and will ask container for 'controller.index.index' class.
This entry key currently is missing. To fix that extend kernel Container.

``` php
<?php

namespace Api\Module;

use SlimApi\Kernel\Container as KernelContainer;

class Container extends KernelContainer
{
    public function initialize()
    {
        parent::initialize();

        $this->initControllers();
    }

    private function initControllers()
    {
        $this['controller.index.index'] = function () {
            return new \Api\Controller\Index\IndexController();
        };
    }
}
```

Last step. Create controller where you respond with Slim\Http\Response

``` php
<?php

namespace Api\Controller\Index;

use Slim\Http\Response;

class IndexController
{
    public function getResponse()
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        $response->setBody(json_encode(array('apple' => 'green'), JSON_UNESCAPED_UNICODE));
        return $response;
    }
}
```


[1]: http://www.slimframework.com
[2]: http://pimple.sensiolabs.org
[3]: http://getcomposer.org