namespace VEL\Includes\Defense\DNS;

class VEL_DNS_Protection {
    protected function setup_dns_security() {
        // DNSSEC 實現
        $this->enable_dnssec([
            'signing_algorithm' => 'RSASHA256',
            'key_length' => 2048,
            'zone_signing' => true
        ]);

        // DNS over HTTPS
        $this->configure_doh([
            'providers' => [
                'cloudflare' => 'https://cloudflare-dns.com/dns-query',
                'google' => 'https://dns.google/dns-query'
            ],
            'fallback_resolver' => '1.1.1.1'
        ]);
    }
}