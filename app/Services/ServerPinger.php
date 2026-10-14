<?php

namespace App\Services;

use App\Support\PublicHost;

class ServerPinger
{
    public function __construct(
        private PublicHost $hosts,
        private StreamSocketConnector $connector,
    ) {}

    public function ping(string $host, int $port, float $timeout = 2.0): ?int
    {
        if ($port < 1 || $port > 65535) {
            return null;
        }

        $address = $this->hosts->connectAddress($host);

        if ($address === null) {
            return null;
        }

        return $this->connector->connect($address, $port, $timeout);
    }
}
