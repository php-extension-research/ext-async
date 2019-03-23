--TEST--
TCP detects pending read.
--SKIPIF--
<?php
if (!extension_loaded('task')) echo 'Test requires the task extension to be loaded';
?>
--FILE--
<?php

namespace Concurrent\Network;

use Concurrent\Stream\PendingReadException;
use Concurrent\Task;
use Concurrent\Timer;

list ($a, $b) = TcpSocket::pair();

Task::async(function () use ($a) {
    var_dump($a->read());
});

(new Timer(20))->awaitTimeout();

try {
    $a->read();
} catch (PendingReadException $e) {
    var_dump($e->getMessage());
} finally {
	$b->close();
}

--EXPECT--
string(41) "Cannot read while another read is pending"
NULL
