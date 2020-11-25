<?php

namespace Cego\AuthMiddleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
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
        if ( ! ($request->hasHeader('remote-user') && $request->hasHeader('remote-user-uuid'))) {
            throw new RemoteUserAuthenticationFailed;
        }

        // To allow the use of Auth::user and related subsystems of Laravel,
        // we must login an authenticatable user model.
        // This can either be for an in-memory model, or one persisted to the database
        Auth::login($this->getAuthenticatedUser($request->header('remote-user'), $request->header("remote-user-uuid")));

        // Proceed to the next middleware
        return $next($request);
    }

    /**
     * Returns the authenticated users model.
     *
     * As the remote-user header contains the username of the user
     * that has been authenticated by authentication proxy, we
     * need to ensure the user is actually known to us. This
     * is done many ways, by fetching the user from the database,
     * creating a new entry for the user or using an in-memory instance.
     * In all cases the user will automatically be logged in.
     *
     * @param string $remoteUser
     * @param string $remoteUserUuid
     *
     * @return Authenticatable
     */
    protected function getAuthenticatedUser(string $remoteUser, string $remoteUserUuid): Authenticatable
    {
        $modelClass = config("auth-middleware.model");
        $modelData = $this->getModelData($remoteUser, $remoteUserUuid);

        // If in-memory only, then there is no need to touch the database and we can opt out here
        if ($this->isInMemoryOnly()) {
            $user = new $modelClass();
            $user->forceFill($modelData);

            return $user;
        }

        return $this->firstOrCreate($modelClass, $modelData);
    }

    /**
     * Fetches the model from the database, or creates it
     *
     * @param mixed $modelClass
     * @param array $modelData
     *
     * @return mixed
     */
    protected function firstOrCreate($modelClass, array $modelData)
    {
        // first
        if (! is_null($instance = $modelClass::where($modelData)->first())) {
            return $instance;
        }

        // or create
        return $modelClass::forceCreate($modelData);
    }

    /**
     * Returns the column an value pairs of the authenticated user.
     *
     * @param string $remoteUser
     * @param string $remoteUserUuid
     *
     * @return string[]
     */
    protected function getModelData(string $remoteUser, string $remoteUserUuid): array
    {
        $data = [config("auth-middleware.column") => $remoteUser];

        if (config("auth-middleware.uuid-primary-key") === true) {
            $data["id"] = $remoteUserUuid;
        }

        return $data;
    }

    /**
     * Returns true if the user model should only be stored in-memory and not persisted to DB
     *
     * @return bool
     */
    protected function isInMemoryOnly(): bool
    {
        return config("auth-middleware.in-memory", false) === true;
    }
}
