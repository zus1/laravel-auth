<?php

namespace Zus1\LaravelAuth\Middleware;

use Illuminate\Contracts\Auth\Authenticatable;
use Zus1\LaravelAuth\Repository\TokenRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomAuth
{
    public function __construct(
        private TokenRepository $tokenRepository,
    ){
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header(config('laravel-auth.token.request_header'));

        if($token === null) {
            throw new HttpException(401, 'Unauthorized');
        }

        try {
            $token = $this->tokenRepository->retrieve($token);
            /** @var Authenticatable $user */
            $user = $token->user()->first();
        } catch(HttpException) {
            throw new HttpException(403, 'Forbidden');
        }

        Auth::login($user);

        return $next($request);
    }
}
