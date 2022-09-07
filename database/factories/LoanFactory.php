<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LoanFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition() {

        $user = User::count() >= 20 ? User::inRandomOrder()->first()->id : User::factory();

        return [
            'amount' => $this->faker->numberBetween($min = 5000, $max = 100000),
            'term' => $this->faker->numberBetween($min = 1, $max = 6),
            'repayment_frequency' => 'weekly',
            'user_id' => $user,
            'status' => 'PENDING'
        ];
    }

    public function approved() {
        return $this->state([
                    'status' => "APPROVED",
        ]);
    }

}
