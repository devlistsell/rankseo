<?php

namespace Acelle\Jobs;

use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class UpdateSegmentJob extends Base implements ShouldBeUnique
{
    protected $segment;

    public function __construct($segment)
    {
        $this->segment = $segment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->segment->updateCache();
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public $uniqueFor = 1200; // 20 minutes
    public function uniqueId()
    {
        return $this->segment->id;
    }

    public function uniqueVia()
    {
        return Cache::driver('file');
    }
}
