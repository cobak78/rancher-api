<?php

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

