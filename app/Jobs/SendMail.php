<?php

namespace App\Jobs;

use App\Mail\OrderShipped;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $tries = 5;

    public function __construct()
    {
        $this->afterCommit();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to('rolalu.me@gmail.com')
            ->send(new OrderShipped);
    }

    // public function retryUntil() : DateTime
    // {
    //     return now()->addMinute();
    // }

    // public function tries(): int
    // {
    //     return 3;
    // }
}
