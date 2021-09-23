<?php

namespace App\WebSocket;

use App\Repository\LoginRepository;
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

    public function onChatMessage($msg) {
        $data = json_decode($msg);

        $loginRepository = new LoginRepository();
        $login = $loginRepository->getLoginsByUserId($data->messaged_user_id);
        $this->users[$login['connection_id']]->send($msg);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        //$numRecv = count($this->clients) - 1;
        if ($msg==="whoami") {
            $from->send($from->resourceId);
            return;
        }

       /* echo "from->>>".json_encode($msg);
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        echo "clients->>>".json_encode($this->clients);
*/
        $data = json_decode($msg);
        if($this->users[$data->messaged_user_id]) {
            $this->users[$data->messaged_user_id]->send($msg);
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