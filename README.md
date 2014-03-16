# SlimApi

API framework based on [slim][1] and [pimple][2]

[![Build Status](https://travis-ci.org/wormhit/slim-api.png?branch=master)](https://travis-ci.org/wormhit/slim-api) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/wormhit/slim-api/badges/quality-score.png?s=043433cd499dcee86d4a27ee62edf0f7280063b3)](https://scrutinizer-ci.com/g/wormhit/slim-api/) [![Code Coverage](https://scrutinizer-ci.com/g/wormhit/slim-api/badges/coverage.png?s=017512f08808dee0c83440b91c9cd996503ccc66)](https://scrutinizer-ci.com/g/wormhit/slim-api/code-structure/master) [![Latest Stable Version](https://poser.pugx.org/wormhit/slim-api/v/stable.png)](https://packagist.org/packages/wormhit/slim-api) [![License](https://poser.pugx.org/wormhit/slim-api/license.png)](https://packagist.org/packages/wormhit/slim-api)

## Installation

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

## Execution

Application can be quickly started by using [php built in web server][4].

### Index

Before starting server edit your index.php file

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

### Server

Start application by executing command from terminal

``` sh
php -S localhost:8000
```

and point your browser to [http://localhost:8080][5]

Initially application will not be return nothing useful except slim frameworks 404 page.

### Routing

To set up routing, please uncomment $configData = array(...) line in index.php file.
Then create new routing class.

*Namespace \Api and path src/ was set up in your composer.json*

``` php
<?php
# src/Module/Routing.php
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

This setup will tell slim framework to match "/" request.
When request is matched, appropriate closure function will be executed.

Usually you will want to point this part of code to get controller and request response object from it.
SlimApi is using pimple as dependency injection container [pimple][2] to keep object initialization and setup in one place.
In this case code is asking container for 'controller.index.index' class.
This entry key now is missing.

### Container

Create custom container by extending SlimApi\Kernel\Container.

Uncomment line in index.php

```php
$container = new Api\Module\Container();
```

and create class container class

``` php
<?php
# src/Module/Container.php
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
Still, now controller class in closure will not be found, because it dose not exist yet.

### Controller

Controller usually should return Slim\Http\Response object.
This response will then be handled by custom routing. In this case Api\Module\Routing.

Create index controller

``` php
<?php
# src/Controller/Index/IndexController.php
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

Now refresh [http://localhost:8080][5] and you should see json response.

## Testing

You can test simple-api files using command
``` sh
./vendor/bin/phpunit -c vendor/wormhit/slim-api/tests/phpunit.xml
```

Library is really simple and easy to understand.
If things dont work out as expected, check terminal output when running php server.


[1]: http://www.slimframework.com
[2]: http://pimple.sensiolabs.org
[3]: http://getcomposer.org
[4]: http://php.net/manual/en/features.commandline.webserver.php
[5]: http://localhost:8080