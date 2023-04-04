<?php

namespace Models\Exceptions;

class AlreadyExistsException extends DatabaseException
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}