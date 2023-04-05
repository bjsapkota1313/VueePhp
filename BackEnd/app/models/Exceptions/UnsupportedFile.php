<?php

namespace Models\Exceptions;

class UnsupportedFile extends FileManagementException
{
    public function __construct($message = "Unsupported File", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}