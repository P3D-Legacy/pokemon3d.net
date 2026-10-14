<?php

namespace App\Services;

class StreamSocketConnector
{
    public function connect(string $address, int $port, float $timeout): ?int
    {
        $target = str_contains($address, ':')
            ? sprintf('tcp://[%s]:%d', $address, $port)
            : sprintf('tcp://%s:%d', $address, $port);

        $start = hrtime(true);

        set_error_handler(static fn (): bool => true);

        try {
            $connection = stream_socket_client(
                $target,
                $errno,
                $errstr,
                $timeout,
            );
        } finally {
            restore_error_handler();
        }

        $elapsedMs = (int) floor((hrtime(true) - $start) / 1_000_000);

        if ($connection === false) {
            return null;
        }

        fclose($connection);

        return $elapsedMs;
    }
}
