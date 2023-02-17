<?php

namespace App\Exceptions;

use Exception;

class GlobalException extends Exception
{
    public $message;
    public function __construct($message)
    {
        $this->message = $message;
        parent::__construct();
    }

    public function render()
    {
        return back()->with("error", $this->message);
    }
}
