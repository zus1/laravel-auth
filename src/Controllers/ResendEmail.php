<?php

namespace Zus1\LaravelAuth\Controllers;

use Illuminate\Http\JsonResponse;
use Zus1\LaravelAuth\Mail\Send;
use Zus1\LaravelAuth\Request\ResendEmailRequest;

class ResendEmail
{
    public function __construct(
        private Send $resend,
    ) {
    }

    public function __invoke(ResendEmailRequest $request): JsonResponse
    {
        $this->resend->resend($request->input('email'), $request->query('type'));

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
