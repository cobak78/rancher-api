# rancher-api

Rancher api client with php7 and PSR7 specification, to execute bash actions on deployed containers

#### Install via composer:
    composer require cobak78/rancher-api

#### Docker
Rancher-api includes a base image with test dependencies installed in.
Use docker-compose file to stand up a fpm container with the app code mounted.

#### Usage

1. Create an script to use api.

```php
require_once dirname(__FILE__) . '/vendor/autoload.php';

use Cobak78\RancherApi\Clients\HttpClient;
use Cobak78\RancherApi\Clients\WSClient;
use Cobak78\RancherApi\RancherApi;

$client = new HttpClient();
$wsClient = new WSClient();

$rancherApi = new RancherApi(
    $rancherHost,
    $rancherKey,
    $rancherSecret
    $client, 
    $wsClient, 
    $argv, 
    $argc
);

$rancherApi->execute();

```

If you need to execute the same script on all deployed containers, modify script.

```php
$rancherApi->execute(true);
```

this php script expects at least three arguments:
1. project name
2. container name
3. command to execute
4. command parameters [optional]

2. Create a bash file or execute this script inside the provided docker container:
```bash
php execute.php project-api fpm bin/console c:c
```

or 

```
$ docker exec [fpm-container] php execute.php project-api fpm bin/console c:c
```

##### What it does

Rancher api, look for projects, service and containers deployed on rancher. If the connection need a web socket to execute bash action, retrieve token from rancher and creates a connection. Then decode and show the response on console.
