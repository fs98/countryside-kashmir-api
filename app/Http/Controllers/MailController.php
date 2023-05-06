<?php

namespace App\Http\Controllers;

use App\Http\Requests\MailRequest;
use App\Mail\MailToClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class MailController extends BaseController
{
    /**
     * Send a mail.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMail(MailRequest $request): JsonResponse
    {
        $mail = Mail::to($request->receiver)
            ->send(new MailToClient($request->subject, $request->message));

        if ($mail) {
            return $this->sendResponse(null, 'Email successfully sent!');
        }

        return $this->sendError(null, 'Email sending failed!');
    }
}
