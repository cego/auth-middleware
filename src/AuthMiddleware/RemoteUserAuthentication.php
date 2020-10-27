<?php

namespace Cego\AuthMiddleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cego\AuthMiddleware\Exceptions\RemoteUserAuthenticationFailed;

class RemoteUserAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     *
     * @throws RemoteUserAuthenticationFailed
     */
    public function handle($request, Closure $next)
    {
        // We expect the remote-user header to be set. This is done by
        // the authentication proxy we need to put in front of this
        // service in order to facilitate authentication.
        if ( ! $request->hasHeader('remote-user')) {
            throw new RemoteUserAuthenticationFailed;
        }

        // As the remote-user header contains the username of the user
        // that has been authenticated by authentication proxy, we
        // need to ensure the user is actually known to us. This
        // is done either by fetching the user from the database
        // or creating a new entry for the user. In both cases
        // the user will be automatically logged in.
        $user = config("auth-middleware.model")::firstOrCreate([
            config("auth-middleware.column") => $request->header('remote-user')
        ]);

        Auth::login($user);

        // Proceed to the next middleware
        return $next($request);
    }
}
