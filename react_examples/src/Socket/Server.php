<?php

require_once('ConnectionPool.php');
require '../../vendor/autoload.php';

use React\Socket\ConnectionInterface;

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server('127.0.0.1:8181', $loop);

initSocketEvents($socket, new ConnectionsPool($socket));

echo "Listening on {$socket->getAddress()}\n";

$loop->run();






/**
 * @param \React\Socket\Server $socket
 * @param ConnectionsPool $pool
 */
function initSocketEvents(\React\Socket\Server $socket, ConnectionsPool $pool)
{
    $userCount = 0;

    $socket->on('connection',
        function (ConnectionInterface $connection) use ($pool, &$userCount) {
            $pool->add($connection);
            echo "User count: " . ++$userCount . "\n";
        }
    );

    $socket->on('userLeave',
        function () use (&$userCount) {
            echo "User count: " . --$userCount . "\n";
        }
    );
}
