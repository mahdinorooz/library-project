<?php

namespace App\Jobs;

use App\Traits\SmsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Sms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SmsTrait;
    protected $mobile;
    protected $text;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mobile, $text)
    {
        $this->mobile = $mobile;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $this->sendSms($this->mobile, $this->text);
    }
}
