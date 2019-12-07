<?php
error_reporting(E_ALL);

echo "TCP/IP connection\n";

$port = 10000;
$address = '127.0.0.1';

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (!$socket) {
   echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "/n";
}

echo "Attempting to connect to {$address} on port {$port}...";
$result = socket_connect($socket, $address, $port);
if(!$result){
    echo "socket_connect() failed.\n Reason: ({$result}) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK.\n";
}

echo "Sending HTTP HEAD request\n";
$inputBuffer = <<<BUFF
HEAD / HTTP/1.1
Host: localhost
Connection: Close

BUFF;


socket_write($socket, $inputBuffer, strlen($inputBuffer));

echo "Reading response:\n\n";
while ($out = socket_read($socket, 2048)) {
    echo $out;
}

echo "Closing socket...";
socket_close($socket);
echo "OK.\n";
