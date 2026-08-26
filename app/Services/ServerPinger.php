<?php

namespace App\Services;

class ServerPinger
{
    public function ping(string $host, int $port, float $timeout = 2.0): ?int
    {
        $start = hrtime(true);

        set_error_handler(static fn (): bool => true);

        try {
            $connection = stream_socket_client(
                sprintf('tcp://%s:%d', $host, $port),
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
