<?php

namespace App\Jobs;

use App\Model\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class update implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $openid;

    /**
     * Create a new job instance.
     * @return void
     */
    public function __construct($openid)
    {
        $this->openid = $openid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    }
}
