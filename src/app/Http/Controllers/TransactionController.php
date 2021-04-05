<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Models\Profile;
use App\Models\Transaction;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class TransactionController extends Controller
{
    private $transaction;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function transact(Request $request)
    {
         $validated = $this->validator($request->all())->validate();

         $profile = (new UserService())->getProfileByUserId($validated['id']);

         DB::beginTransaction();

        $this->transaction::create([
            'user_id' => $profile->user_id,
            'type' => $validated['type'],
            'value' => $validated['value'],
            'description' => $this->transaction::getDescription($validated['value']),
        ]);

        switch ($validated['type'])
        {
            case ($validated['type'] == env('TYPE_REFILL')):
            $profile->balance += abs($validated['value']);
            DB::commit();
            $profile->save();
            return response()->json(['balance' => 'Операция прошла успешно'], 201);

            case ($validated['type'] == env('TYPE_DEBIT')):
             if (($profile->balance - abs($validated['value'])) < 0) 
                {
                DB::rollback(); 
                return response()->json(['balance' => 'У вас недостаточно средств'], 404);
                } else {
                 $profile->balance -= abs($validated['value']);
                 DB::commit();               
                 $profile->save();
                 return response()->json(['balance' => 'Операция прошла успешно'], 201);
                 }

             default:
                DB::rollback(); 
                return response()->json(['type' => 'Введите верное значение'], 404);

        }


    }

     public function refund(Request $request)
    {

        $profile = (new UserService())->getProfileByUserId($request->id);

        if ($request->type == env('TYPE_REFUND'))
        {
            return response()->json(['Ваш баланс: '.$profile->balance.' руб.'], 201);
        } else {
            return response()->json(['type' => 'Введите верное значение'], 404);
        }

    }

    public function cancel(Request $request, $id)
    {

        $trans = (new UserService())->getTransactionsByUserId($request->id);
        $profile = (new UserService())->getProfileByUserId($request->id);

        foreach($trans as $tran){
            if ($tran->id == $id){
                $description = $tran->description;
                $value = $tran->value;
            }
        }

        if (!isset($description) || !isset($value)){
            return response()->json(['id' => 'Такой транзакции не существует'], 404); 
        }


       if (str_contains($description, 'Пополнение') || str_contains($description, 'Списание'))
       {            
            $profile->balance += $value;
            $profile->save();
            return response()->json(
                ['Операция выплнена, ваш баланс: '.$profile->balance.' руб.'], 201
            ); 
        } else {
            return response()->json(['id' => 'Такой транзакции не существует'], 404);
               };

        }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'id' => ['required'],
            'type' => ['required', 'numeric', 'between:1,3'],
            'value' => ['required', 'numeric']
        ]);
    }



}
