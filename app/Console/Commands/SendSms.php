<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\UserAlert;
use App\Models\Seller;
use Mail;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email to user';

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

        $users = Seller::all();
        foreach($users as $user) {
            Mail::to($user->email)->send(new UserAlert());
        }
    }
}
