<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class CarDetachingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::first();
        $this->route = route('users.detach.car', [
            'user' => $this->user,
        ]);
    }

    public function test_we_can_detach_user_from_car(): void
    {
        $this->json('delete', $this->route)
            ->assertJsonFragment([
                'message' => 'user removed from car'
            ])->assertSuccessful();

        self::assertNull($this->user->currentCar());
    }
}
