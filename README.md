[![Build Status](https://travis-ci.org/wormhit/slim-api.png?branch=master)](https://travis-ci.org/wormhit/slim-api) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/wormhit/slim-api/badges/quality-score.png?s=043433cd499dcee86d4a27ee62edf0f7280063b3)](https://scrutinizer-ci.com/g/wormhit/slim-api/) [![Code Coverage](https://scrutinizer-ci.com/g/wormhit/slim-api/badges/coverage.png?s=017512f08808dee0c83440b91c9cd996503ccc66)](https://scrutinizer-ci.com/g/wormhit/slim-api/code-structure/master) [![Latest Stable Version](https://poser.pugx.org/wormhit/slim-api/v/stable.png)](https://packagist.org/packages/wormhit/slim-api) [![License](https://poser.pugx.org/wormhit/slim-api/license.png)](https://packagist.org/packages/wormhit/slim-api)

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
//$configData = array(SlimApi\Kernel\Config::ROUTING => 'Api\Module\Routing');
//$configData = include 'config/config.php';

$config    = new SlimApi\Kernel\Config();
$container = new SlimApi\Kernel\Container();
//$container = new Api\Module\Container();

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
You should see slim frameworks 404 page.

Configure
-----

To create new routing path uncomment $configData = array(...) line in index.php and
create new routing class under src/Module/Routing.php (was set up in your composer.json)

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

        return $this;
    }
}
```

Now your custom routing will match "/" and will ask container for 'controller.index.index' class.
This entry key currently is missing.
To fix that uncomment your custom container line in index.php (//$container = new Api\Module\Container();)
and create it under src/Module/Container.php


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

Now your script will be able to find 'controller.index.index' key.
But controller class in closure will be not found because it dose not exist yet.
So last step - create controller where you respond with Slim\Http\Response
Just create it under src/Controller/Index/IndexController.php

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

Now you should see json response in browser.

Testing
-----

You can test simple-api files using command
``` sh
./vendor/bin/phpunit -c vendor/wormhit/slim-api/tests/phpunit.xml
```

Library is really simple and easy to understand.
If things dont work out as expected, check terminal output when running php server.


[1]: http://www.slimframework.com
[2]: http://pimple.sensiolabs.org
[3]: http://getcomposer.org