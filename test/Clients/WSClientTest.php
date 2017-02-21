<?php

namespace Cobak78\RancherApi\Clients;

use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message;

/**
 * Class WSClientTest
 * @package Cobak78\RancherApi\Clients
 */
class WSClientTest
{

    /**
     * @param string $wsUrl
     * @param string $token
     */
    public function socketConnect(string $wsUrl, string $token)
    {
        /** @var $conn WebSocket */
        \Ratchet\Client\connect($wsUrl . '?token=' . $token)
            ->then(function($conn) {

                /** @var $msg Message */
                $conn->on('message', function($msg) use ($conn) {

                    $msg = base64_decode($msg->getPayload());

                    echo "Received: {$msg}\n";

                    $conn->close();
                });

            }, function ($e) {

                echo "Could not connect: {$e->getMessage()}\n";
            });
    }

}
