<?php

namespace App\Exceptions;

use Exception;

class DatabaseException extends Exception
{
    public $route;
    public $message;
    public function __construct($message, $route)
    {
        $this->route = $route;
        $this->message = $message;
        parent::__construct();
    }

    public function render()
    {
        if($this->route == 'api'){
            return response()->json(['status' => 'error', 'message' => $this->route ], 400);
        }
        return back()->with("error", $this->message);
    }
}
