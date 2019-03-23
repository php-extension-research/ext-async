--TEST--
Channel buffer will be released.
--SKIPIF--
<?php
if (!extension_loaded('task')) echo 'Test requires the task extension to be loaded';
?>
--FILE--
<?php

namespace Concurrent;

$channel = new Channel(1);

$channel->send(new class() {
    public function __destruct() {
        var_dump('DTOR');
    }
});

var_dump('START');
$channel = null;
var_dump('END');

--EXPECT--
string(5) "START"
string(4) "DTOR"
string(3) "END"
