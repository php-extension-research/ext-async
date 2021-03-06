--TEST--
Process provides STDOUT as readable pipe.
--SKIPIF--
<?php require __DIR__ . '/skipif.inc'; ?>
--FILE--
<?php

namespace Concurrent\Process;

$builder = new ProcessBuilder(PHP_BINARY);
$builder = $builder->withStdoutPipe();
$builder = $builder->withoutStderr();

$process = $builder->start(__DIR__ . '/assets/running.php');

var_dump($process->isRunning(), $process->getPid() > 0, 'START');

$stdout = $process->getStdout();

var_dump($stdout instanceof \Concurrent\Stream\ReadableStream);

try {
    while (null !== ($chunk = $stdout->read())) {
        echo $chunk;
    }
} finally {
    $stdout->close();
}

var_dump($process->join());
var_dump($process->join());

var_dump($process->isRunning(), 'FINISHED');

--EXPECT--
bool(true)
bool(true)
string(5) "START"
bool(true)
string(7) "RUNNING"
int(7)
int(7)
bool(false)
string(8) "FINISHED"
