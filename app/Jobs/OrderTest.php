<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\OrderShipped as OrderShipped;
use Mail;
class OrderTest implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $demo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($demo)
    {
        $this->demo = $demo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new OrderShipped($this->demo);
        Mail::to('laduanxun98@gmail.com')->send($email);
    }
}
