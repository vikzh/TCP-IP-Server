<?php

error_reporting(E_ALL);

set_time_limit(0);

ob_implicit_flush();

$address = '127.0.0.1';
$port = 10000;

if (!($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (!socket_bind($socket, $address, $port)) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
}

if (!socket_listen($socket, 5)) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
}

do {
    if(!($messagesSocket = socket_accept($socket))) {
        echo "socket_accept failed: reason: " . socket_strerror(socket_last_error($socket)) . "\n";
        break;
    }

    $message = "\n Welcome to the PHP Server. \n";
    socket_write($messagesSocket, $message, strlen($message));

    do {
        $buf = socket_read($messagesSocket, 2048, PHP_NORMAL_READ);
        if (!$buf) {
            echo "socket_read() failed: reason: " . socket_strerror(socket_last_error($messagesSocket)) . "\n";
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
            socket_close($messagesSocket);
            break 2;
        }

        $talkBack = "PHP: You said '$buf'.\n";
        socket_write($messagesSocket, $talkBack, strlen($talkBack));
        echo "$buf\n";
    } while (true);
    socket_close($messagesSocket);
} while(true);

socket_close();
