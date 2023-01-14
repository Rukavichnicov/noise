<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send()
    {
        Mail::send('mail', ['text' => 'mail', 'name' => 'Sergey'], function ($message) {
            $message->to('rukavichnicov8@gmail.com', 'Получатель')->subject('test email');
            $message->from(env('MAIL_USERNAME'), 'noisesources.ru');
        });
    }
}
