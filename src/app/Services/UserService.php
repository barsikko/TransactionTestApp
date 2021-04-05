<?php 


namespace App\Services;

use App\Models\Profile;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Validator;



/**
 * 
 * Returns User Proifile by id 
 * 
 */


class UserService
{

	public function getProfileByUserId($id) : Profile
	{

        $user = User::find($id);

         if (!$user){
            return response()->json(['user' => 'Пользователь не найден']);
        }

        return $user->profile;
	}


	public function getTransactionsByUserId($id)
	{
		$user = User::find($id);

		 if (!$user){
            return response()->json(['user' => 'Пользователь не найден']);
        }

     	return $user->transactions;   

	}
/*
	  private function validator(array $id)
    {
        return Validator::make($id, [
            'id' => ['required'],
        ]);
    }*/
 
}