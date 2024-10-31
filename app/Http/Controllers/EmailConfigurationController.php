<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\DynamicEmail;
use Mail;
class EmailConfigurationController extends Controller
{
    public function composeEmail() {
        return view("email");
    }
    public function sendEmail(Request $request) {
        $toEmail    =   $request->emailAddress;
        $data       =   array(
            "message"    =>   $request->message
        );

        // pass dynamic message to mail class
        Mail::to($toEmail)->send(new DynamicEmail($data));

        if(Mail::failures() != 0) {
            return back()->with("success", "E-mail sent successfully!");
        }

        else {
            return back()->with("failed", "E-mail not sent!");
        }
    }
}
