<?php

namespace Database\Factories;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class TransactionFactory
 * @package Database\Factories
 */
class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $value = rand(-1000, 1000);

        return [
            'created_at' => Carbon::now()->subMinutes(rand(0, 10000)),
            'value' => $value,
            'type' => $value < 0 ? env('TYPE_DEBIT') : env('TYPE_REFILL'),
            'description' => Transaction::getDescription($value)
        ];
    }

}
