# rancher-api
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cobak78/rancher-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cobak78/rancher-api/?branch=master)

Rancher api client with php7 and PSR7 specification, to execute bash actions on deployed containers

#### Install via composer:
    composer require cobak78/rancher-api

#### Docker
Rancher-api includes a base image with test dependencies installed in.
Use docker-compose file to stand up a fpm container with the app code mounted.

#### Usage
Create an script to use api.

```php

define('DOCUMENT_ROOT', dirname(__FILE__));

require_once DOCUMENT_ROOT . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Cobak78\RancherApi\Clients\HttpClient;
use Cobak78\RancherApi\Clients\WSClient;
use Cobak78\RancherApi\RancherApi;

$dotenv = new Dotenv(DOCUMENT_ROOT);
$dotenv->load();

$client = new HttpClient();
$wsClient = new WSClient();

$rancherApi = new RancherApi($client, $wsClient, $argv, $argc);

$rancherApi->execute();

```

If you need to execute the same script on all deployed containers, modify script.

```php
$rancherApi->execute(true);
```

this api expects at least three arguments:
1. project name
2. container name
3. command to execute
4. command parameters [optional]
 
```bash
php execute.php project-api fpm bin/console c:c
```


##### What it does

Rancher api, look for projects, service and containers deployed on rancher. If the connection need a web socket to execute bash action, retrieve token from rancher and creates a connection. Then decode and show the response on console.
