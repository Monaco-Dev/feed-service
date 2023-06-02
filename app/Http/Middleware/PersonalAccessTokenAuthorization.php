<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use App\Models\User;
use App\Sources\Auth\Contracts\AuthSourceInterface;

class PersonalAccessTokenAuthorization
{
    /**
     * @var \App\Sources\Auth\Contracts\AuthSourceInterface
     */
    protected $authSource;

    /**
     * Instantiate dependencies
     * 
     * @param \App\Sources\Auth\Contracts\AuthSourceInterface $authSource
     * @return void
     */
    public function __construct(AuthSourceInterface $authSource)
    {
        $this->authSource = $authSource;
    }

    /**
     * Handle an incoming request.
     * Verify Request token via password grant verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) abort(401, 'Unauthenticated');

        $response = $this->authSource->verifyToken($token);

        $user = new User(collect($response)->toArray());

        if (!$user->is_email_verified) {
            abort(403, 'Your email address is not verified.');
        }

        if (!optional($user->broker_license)->is_license_verified) {
            abort(403, 'Your license number is not verified.');
        }

        // login the user via auth
        auth()->login($user);

        // login the user via request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
