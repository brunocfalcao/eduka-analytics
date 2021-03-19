<?php

namespace Eduka\Analytics\Jobs;

use Eduka\Abstracts\EdukaJob;
use Eduka\Analytics\Models\IpAddress;

class CheckIpForBlacklisting extends EdukaJob
{
    public $ip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dnsLookups = config('eduka-analytics.dns.blacklist_servers');

        $reverseIp = implode('.', array_reverse(explode('.', $this->ip)));

        foreach ($dnsLookups as $host) {
            if (checkdnsrr($reverseIp.'.'.$host.'.', 'A')) {
                IpAddress::where('ip_address', $this->ip)
                         ->update(['is_blacklisted' => true]);
            }
        }
    }
}
