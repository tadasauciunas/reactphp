<?php

require '../../vendor/autoload.php';

use React\Socket\ConnectionInterface;

class ConnectionsPool
{
    /** @var ConnectionInterface[] */
    private $connections = [];

    /** @var \React\Socket\Server */
    private $socket;

    /**
     * @param \React\Socket\Server $socket
     */
    public function __construct(\React\Socket\Server $socket)
    {
        $this->socket = $socket;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function add(ConnectionInterface $connection)
    {
        $this->connections[] = $connection;

        $connection->on('data', function ($data) use ($connection) {
            $this->sendAll($data, $connection);
        });

        $connection->on('close', function () {
            $this->socket->emit('userLeave');
        });
    }

    /**
     * @param array $data
     * @param ConnectionInterface $sender
     */
    private function sendAll($data, ConnectionInterface $sender)
    {
        foreach ($this->connections as $conn) {
            if ($conn != $sender) {
                $conn->write($data);
            }
        }
    }
}
