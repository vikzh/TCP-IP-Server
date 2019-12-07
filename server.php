<?php

error_reporting(E_ALL);

set_time_limit(0);

ob_implicit_flush();

$address = '127.0.0.1';
$port = 10000;

if (($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($socket, $address, $port) === false) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
}

if (socket_listen($socket, 5) === false) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
}

do {
    if(($msgsocket = socket_accept($socket)) === false) {
        echo "socket_accept failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
        break;
    }

    $message = "\n Welcome to the PHP Server. \n";
    socket_write($msgsocket, $message, strlen($message));

    do {
        $buf = socket_read($msgsocket, 2048, PHP_NORMAL_READ);
        if (!$buf) {
            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($msgsocket)) . "\n";
            break 2;
        }

        $buf = trim($buf);
        if (!$buf) {
            continue;
        }

        if ($buf === 'quit') {
            break;
        }

        if ($buf === 'shutdown') {
            socket_close($msgsocket);
            break 2;
        }

        $talkback = "PHP: You said '$buf'.\n";
        socket_write($msgsocket, $talkback, strlen($talkback));
        echo "$buf\n";
    } while (true);
    socket_close($msgsocket);
} while(true);

socket_close();
