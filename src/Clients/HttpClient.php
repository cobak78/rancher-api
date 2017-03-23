<?php

namespace Cobak78\RancherApi\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpClient
 * @package Cobak78\RancherApi\Clients
 */
class HttpClient
{
    /**
     * @var string
     */
    private $accessKey;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $host;

    /**
     * true if the last response returns
     * @var bool
     */
    private $isSocket;

    /**
     * HttpClient constructor.
     * @param string $accessKey
     * @param ClientInterface $httpClient
     * @param string $secretKey
     * @param string $host
     */
    public function __construct(
        string $accessKey,
        ClientInterface $httpClient,
        string $secretKey,
        string $host)
    {
        $this->accessKey = $accessKey;
        $this->httpClient = $httpClient;
        $this->secretKey = $secretKey;
        $this->host = $host;
        $this->isSocket = false;
    }

    /**
     * get isSocket
     *
     * @return bool
     */
    public function isSocket()
    {
        return $this->isSocket;
    }

    /**
     * @param $uri
     * @param bool $withHost
     *
     * @return int|mixed
     */
    public function get($uri, $withHost = false)
    {
        try {

            return json_decode($this->request('GET', $uri, $withHost)->getBody());

        } catch (ClientException $exception) {

            return $exception->getCode();
        }
    }

    /**
     * @param $uri
     * @param array $data
     * @param bool $withHost
     *
     * @return mixed|ResponseInterface
     */
    public function post($uri, array $data = [], $withHost = false)
    {
        return $this->request('post', $uri, $withHost, [
            'body' => json_encode($data)
        ]);
    }

    /**
     * @param ClientInterface $httpClient
     *
     * @return $this
     */
    public function setHttpClient(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param $method
     * @param $uri
     * @param $withHost
     * @param array $data
     * @return mixed|ResponseInterface
     */
    private function request($method, $uri, $withHost, $data = [])
    {
        $data['auth'] = [$this->accessKey, $this->secretKey];

        $response = $this->httpClient->request($method, ($withHost) ? $uri : $this->host . $uri, $data);

        $body = $response->getBody();

        $this->parseResponse($response);

        $body->rewind();

        return $response;
    }

    /**
     * @param ResponseInterface $response
     */
    private function parseResponse(ResponseInterface $response)
    {
        $this->isSocket = (strpos($response->getBody()->getContents(), 'ws:')) ? true : false;
    }
}
