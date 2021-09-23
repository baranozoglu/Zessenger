<?php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    private $users;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->users = array();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
        $this->users[$conn->resourceId] = $conn;

    }


    public function onMessage(ConnectionInterface $from, $msg) {
        if ($msg==="whoami") {
            $from->send($from->resourceId);
            return;
        }

        echo $msg;
        $data = json_decode($msg);
        if ($data->connection_id && $data->user_id){
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    $client->send($msg);
                }
            }
        }

        if($this->users[$data->messaged_connection_id]) {
            $this->users[$data->messaged_connection_id]->send($msg);
        }

    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}