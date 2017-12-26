<?php

namespace SocialRocket\Exceptions;

use Throwable;

class AuthenticationException extends \Exception
{

    /**
     * AuthenticationException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}