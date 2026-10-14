<?php

namespace App\Support;

use Closure;

class PublicHost
{
    /**
     * @param  (Closure(string): list<string>)|null  $resolver
     */
    public function __construct(private ?Closure $resolver = null) {}

    public function connectAddress(string $host): ?string
    {
        $host = strtolower(trim($host));

        if ($host === '' || $this->containsUnsafeCharacters($host) || $this->isLocalName($host)) {
            return null;
        }

        if (str_starts_with($host, '[') && str_ends_with($host, ']')) {
            $host = substr($host, 1, -1);
        }

        if (filter_var($host, FILTER_VALIDATE_IP) !== false) {
            return $this->isPublicIp($host) ? $host : null;
        }

        if (filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            return null;
        }

        $addresses = $this->resolve($host);

        if ($addresses === []) {
            return null;
        }

        foreach ($addresses as $address) {
            if (! $this->isPublicIp($address)) {
                return null;
            }
        }

        return $addresses[0];
    }

    /**
     * @return list<string>
     */
    private function resolve(string $host): array
    {
        if ($this->resolver instanceof Closure) {
            return ($this->resolver)($host);
        }

        $records = dns_get_record($host, DNS_A | DNS_AAAA);

        if ($records === false) {
            return [];
        }

        $addresses = [];

        foreach ($records as $record) {
            if (isset($record['ip']) && is_string($record['ip'])) {
                $addresses[] = $record['ip'];
            }

            if (isset($record['ipv6']) && is_string($record['ipv6'])) {
                $addresses[] = $record['ipv6'];
            }
        }

        return $addresses;
    }

    private function isPublicIp(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }

        return ! $this->isCarrierGradeNat($ip);
    }

    private function isCarrierGradeNat(string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
            return false;
        }

        $address = sprintf('%u', ip2long($ip));

        return $address >= sprintf('%u', ip2long('100.64.0.0'))
            && $address <= sprintf('%u', ip2long('100.127.255.255'));
    }

    private function containsUnsafeCharacters(string $host): bool
    {
        return strpbrk($host, " \t\r\n\0/@#?\\") !== false;
    }

    private function isLocalName(string $host): bool
    {
        $host = rtrim($host, '.');

        return $host === 'localhost'
            || str_ends_with($host, '.localhost')
            || str_ends_with($host, '.local');
    }
}
