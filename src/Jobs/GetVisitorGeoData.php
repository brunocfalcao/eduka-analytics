<?php

namespace Eduka\Analytics\Jobs;

use Eduka\Abstracts\EdukaJob;
use Eduka\Analytics\Models\Visitor;
use Illuminate\Support\Facades\Http;

class GetVisitorGeoData extends EdukaJob
{
    public $id;
    public $ip;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $id, string $ip)
    {
        $this->id = $id;
        $this->ip = $ip == '127.0.0.1' ? '188.62.12.60' : $ip;

        $this->onQueue(queue_name('geoip-data'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Make the API call with a specific number of fields.
        $response = Http::get('http://ip-api.com/json/'.$this->ip.'?fields=12108287')
                        ->json();

        if ($response['status'] == 'success') {
            Visitor::firstWhere('id', $this->id)->updateGeoData($response);
        }
    }
}
