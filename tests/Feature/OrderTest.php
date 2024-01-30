<?php

namespace Tests\Feature;

use App\Jobs\SendMail;
use App\Mail\OrderShipped;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * .
     */

    public function test_order_shipped_mail(): void
    {
        Mail::fake();
        SendMail::dispatchAfterResponse();
        Mail::assertSent(OrderShipped::class);
        // $email = new OrderShipped;
    }
}
