<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\CarIsBusyException;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(): LengthAwarePaginator
    {
        return Car::with('users')
            ->paginate();
    }

    public function show(Car $car)
    {
        return $car->load('users');
    }

    public function store(Request $request): JsonResponse
    {
        $car = new Car();
        $car->name = $request->name;
        $car->save();

        return new JsonResponse([
            'data' => $car
        ]);
    }

    /**
     * @throws CarIsBusyException
     */
    public function assignUser(Car $car, User $user): JsonResponse
    {
        $car->giveTo($user);

        return new JsonResponse([
            'message' => 'car was successfully given to this user'
        ]);
    }

    public function detachUser(Car $car): JsonResponse
    {
        $car->removeUser();

        return new JsonResponse([
            'message' => 'user removed from car'
        ]);
    }
}
