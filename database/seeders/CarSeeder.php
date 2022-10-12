<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        Car::factory(10)->create()
            ->each(function (Car $car) use ($users) {
                $randomUser = fake()->unique()->randomElement($users);

                $car->giveTo($randomUser);
            });
    }
}
