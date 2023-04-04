<?php

namespace Models\Exceptions;

class NotFoundException extends DatabaseException
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}