<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\NewOfferEmail;
use App\Models\Customer;
use Mail;

class OfferEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offer:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $users = Customer::all();
        foreach($users as $user) {
            Mail::to($user->email)->send(new NewOfferEmail());
        }
    }
}
