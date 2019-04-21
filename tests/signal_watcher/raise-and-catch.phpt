--TEST--
Signal watcher can raise and catch signals.
--SKIPIF--
<?php
if (!extension_loaded('task')) echo 'Test requires the task extension to be loaded';
?>
--FILE--
<?php

namespace Concurrent;

Task::async(function () {
    $timer = new Timer(100);
    $timer->awaitTimeout();
    
    var_dump('TRIGGER');
    SignalWatcher::raise(SignalWatcher::SIGUSR1);
    
    $timer->awaitTimeout();
    
    var_dump('TRIGGER');
    SignalWatcher::raise(SignalWatcher::SIGUSR1);
});

$signal = new SignalWatcher(SignalWatcher::SIGUSR1);

var_dump('AWAIT SIGNAL');
$signal->awaitSignal();

var_dump('AWAIT SIGNAL');
$signal->awaitSignal();

var_dump('CLOSE');
$signal->close();

try {
    $signal->awaitSignal();
} catch (\Throwable $e) {
    var_dump($e->getMessage());
}

--EXPECT--
string(12) "AWAIT SIGNAL"
string(7) "TRIGGER"
string(12) "AWAIT SIGNAL"
string(7) "TRIGGER"
string(5) "CLOSE"
string(30) "Signal watcher has been closed"
