<?php

include '../../vendor/autoload.php';

use React\EventLoop\Factory as EventLoopFactory;
use React\Stream\ReadableResourceStream;

$loop = EventLoopFactory::create();

$timer = $loop->addPeriodicTimer(0.0001, function () {
    echo " ";
});

$stream = new ReadableResourceStream(fopen('HelloWorld.txt', 'r'), $loop, 1);

$stream->on('data', function ($data) {
    echo $data;
});

$stream->on('close', function () use ($timer, $loop) {
    $loop->cancelTimer($timer);
});

$loop->run();