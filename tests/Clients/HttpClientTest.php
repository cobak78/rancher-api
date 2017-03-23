<?php

namespace Cobak78\RancherApi\Tests\Clients;

use Cobak78\RancherApi\Clients\HttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class HttpClientTest
 * @package Cobak78\RancherApi\Tests\Clients
 */
class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    const
        ACCESS_HOST = 'http://rancher.fake.com',
        ACCESS_APIKEY = '123456789',
        ACCESS_SHARED = '123456789'
    ;

    public function testGet()
    {
        $client = static::createHttpClient();

        $this->assertEquals($client->get('fake'), 404);
    }

    public function testPost()
    {
        $client = static::createHttpClient();

        $this->assertEquals($client->post('fake'), 404);
    }

    public function testConstruct()
    {
        $client = static::createHttpClient();

        $this->assertInstanceOf($client, HttpClient::class);
        $this->assertFalse($client->isSocket());
    }

    /**
     * @return HttpClient
     */
    public static function createHttpClient()
    {
        return new HttpClient(
            static::ACCESS_APIKEY,
            new Client(),
            static::ACCESS_SHARED,
            static::ACCESS_HOST
        );
    }
}
