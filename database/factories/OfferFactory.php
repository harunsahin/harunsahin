<?php

namespace Database\Factories;

use App\Models\Offer;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition()
    {
        $checkin_date = $this->faker->dateTimeBetween('now', '+2 months');
        $checkout_date = $this->faker->dateTimeBetween($checkin_date, '+1 week');
        
        return [
            'agency' => $this->faker->company,
            'company' => $this->faker->company,
            'full_name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'checkin_date' => $checkin_date,
            'checkout_date' => $checkout_date,
            'room_count' => $this->faker->numberBetween(1, 5),
            'pax_count' => $this->faker->numberBetween(1, 10),
            'option_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status_id' => Status::inRandomOrder()->first()->id ?? 1,
            'notes' => $this->faker->optional()->text
        ];
    }
} 