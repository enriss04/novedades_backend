<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CustomError extends Exception
{
    protected $data;

    public function __construct($msg, $code = 500, Throwable $previous = null, $data = null){
        parent::__construct($msg, $code, $previous);
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}