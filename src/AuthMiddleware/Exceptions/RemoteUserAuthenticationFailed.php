<?php

namespace Cego\AuthMiddleware\Exceptions;

use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RemoteUserAuthenticationFailed extends HttpException
{
    /**
     * RemoteUserAuthenticationFailed constructor.
     *
     * @param Throwable|null $previous
     */
    public function __construct(Throwable $previous = null)
    {
        $code = 401;
        $message = 'Authentication by remote-user header could not be validated';

        parent::__construct($code, $message, $previous, [], $code);
    }
}
