<?php

namespace Cobak78\RancherApi;

use Cobak78\RancherApi\Clients\HttpClient;
use Cobak78\RancherApi\Clients\WSClient;

class RancherApi
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var WSClient
     */
    private $wsClient;

    /**
     * @var array
     */
    private $args;

    /**
     * RancherApi constructor.
     * @param HttpClient $client
     * @param WSClient $wsClient
     * @param array $argv
     * @param int $argc
     */
    public function __construct(
        HttpClient $client,
        WSClient $wsClient,
        array $argv,
        int $argc
    )
    {
        $this->client = $client;
        $this->wsClient = $wsClient;

        if (!isset($argv[1]) || !isset($argv[2]) || !isset($argv[3])) {
            throw new \UnexpectedValueException('Unknown environment');
        }

        $this->args['projectArg'] = $argv[1];
        $this->args['containerArg'] = $argv[2];
        $this->args['commandArg'] = $argv[3];

        for ($i = 4; $i <= $argc; $i++) {

            if (!array_key_exists($i, $argv)) break;

            $this->args['params'][] = $argv[$i];
        }
    }


    private function getStack($url = '1a1/stacks/')
    {
        $stacks = $this->client->get($url)->data;

        foreach ($stacks as $stack)
        {
            if ($stack->name == $this->args['projectArg']) {
                return $stack->links->services;
            }
        }
    }

    private function getService($url)
    {
        $services = $this->client->get($url, true)->data;

        foreach ($services as $service)
        {
            if ($service->name == $this->args['containerArg']) {
                return $service->links->self;
            }
        }
    }

    private function getContainers($url)
    {
        $fpmService = $this->client->get($url, true);

        $instances = $this->client->get($fpmService->instances, true);

        return $instances->data;


    }

    private function executeOn($container)
    {
        $response = $this->client->post($container->actions->execute, [
            "attachStdin" => true,
            "attachStdout" => true,
            "command" =>
                array_merge([$this->args['commandArg']], $this->args['params']),
            "tty" => false
        ], true);

        $contents = json_decode($response->getBody()->getContents(), true);

        if ($this->client->isSocket()) {

            $wsClient = new WSClient();

            $wsClient->socketConnect($contents['url'], $contents['token']);
        }
    }

    /**
     * @param bool $onAllContainers
     */
    public function execute($onAllContainers = false)
    {
        $stack = $this->getStack();

        $service = $this->getService($stack);

        $containers = $this->getContainers($service);

        foreach ($containers as $container)
        {
            $this->executeOn($container);

            if ($onAllContainers) {
                break;
            }
        }
    }
}
