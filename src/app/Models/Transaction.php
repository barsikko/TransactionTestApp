<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'value', 'description', 'type', 'created_at',
    ];

        /**
     * @param $value
     * @return string
     */
    public static function getDescription($value) : string
    {
        $text = $value < 0 ? 'Списание' : 'Пополнение';
        $text .= ' на сумму ' . $value . ' '  . Lang::choice('рубль|рубля|рублей', $value, [], 'ru');

        return $text;
    }
}
