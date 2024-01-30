<?php

namespace App\Http\Controllers\Api\V1\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;

class MailController extends Controller
{
    #[NoReturn]
    public function sendMail(Request $request)
    {
        $mail_data = [
            'subject' => 'Site Upgrade. Please change your password.',
        ];

        $job = (new SendEmail($mail_data))
            ->delay(now()->addSeconds(2));

        dispatch($job);

        // response()->json('Email sent', '202');
        echo 'Mail send successfully !!';
        // dd("Job dispatched.");
    }
}
