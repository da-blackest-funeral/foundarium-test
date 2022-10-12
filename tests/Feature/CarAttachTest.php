<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\CarIsBusyException;
use App\Models\Car;
use App\Models\User;
use Tests\TestCase;

class CarAttachTest extends TestCase
{
    protected Car $car;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->car = Car::first();
        $this->user = User::first();

        $this->route = route('cars.attach.user', [
            'car' => $this->car,
            'user' => $this->user,
        ]);
    }

    public function test_user_can_drive_a_car()
    {
        $this->car->removeUser();

        $this->json('post', $this->route)
            ->assertSuccessful();

        self::assertEquals($this->car->currentUser()->id, $this->user->id);
    }

    /**
     * @throws CarIsBusyException
     */
    public function test_if_user_already_attached_to_car_there_will_be_thrown_exception()
    {
        $randomUser = User::find(rand(2, 4));

        \DB::table('car_user')->delete();

        $this->car->giveTo($randomUser);

        $this->json('post', $this->route)
            ->assertStatus(400)
            ->assertJsonFragment([
                'message' => 'This car is busy now.',
            ]);

        self::assertEquals($this->car->currentUser()->id, $randomUser->id);
    }
}
