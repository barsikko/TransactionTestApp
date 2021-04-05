<?php

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function report()
    {

    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function render($request)
    {
    	return response()->json(['profile' => 'Пользователь не найден']);
    } 
}
