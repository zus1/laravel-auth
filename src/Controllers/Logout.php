<?php

namespace Zus1\LaravelAuth\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelAuth\Events\LoggedOutEvent;
use Zus1\LaravelAuth\Repository\TokenRepository;

class Logout
{
    public  function __construct(
        private TokenRepository $repository,
    ){
    }

    public function __invoke(Request $request): JsonResponse {
        if(!$request->hasHeader(config('laravel-auth.token.request_header'))) {
            throw new HttpException(400, 'Authentication header is not set, access token not sent');
        }

        try {
            $token = $this->repository->retrieve($request->header(config('laravel-auth.token.request_header')));
        } catch (HttpException) {
            throw new HttpException(400, 'Already logged out');
        }

        $this->repository->deactivate($token);

        LoggedOutEvent::dispatch();

        return new JsonResponse([], JsonResponse::HTTP_NO_CONTENT);
    }
}
