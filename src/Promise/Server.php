<?php

include '../../vendor/autoload.php';

use React\EventLoop\Factory as EventLoopFactory;

$deferred = new React\Promise\Deferred();

$loop = EventLoopFactory::create();

$promise = $deferred->promise();
$promise->done(function ($data) {
    echo PHP_EOL . 'Done: ' . $data . PHP_EOL;
});

$counter = 0;
$timer = $loop->addPeriodicTimer(0.5,
    function (\React\EventLoop\Timer\TimerInterface $timer) use (&$counter, $loop, $deferred) {
        echo ++$counter;
        if ($counter == 5) {
            $timer->cancel();
            $deferred->resolve('hello world');
        }

    });

$loop->run();