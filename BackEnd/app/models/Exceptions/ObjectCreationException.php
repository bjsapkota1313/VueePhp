<?php
namespace Models\Exceptions;


class ObjectCreationException extends InternalErrorException
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}