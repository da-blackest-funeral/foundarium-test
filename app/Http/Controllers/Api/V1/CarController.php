<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\UserAlreadyIsDrivingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class CarController extends Controller
{
    public function index(): LengthAwarePaginator
    {
        return tap(Car::with('users')
            ->paginate())
            ->transform(function (Car $car) {
                return new CarResource($car);
            });
    }

    public function show(Car $car): CarResource
    {
        return new CarResource($car);
    }

    public function store(CarRequest $request): JsonResponse
    {
        $car = new Car();
        $car->name = $request->name;
        $car->save();

        return new JsonResponse([
            'data' => $car
        ]);
    }

    public function update(Car $car, CarRequest $request): JsonResponse
    {
        $car->name = $request->name;
        $car->save();

        return new JsonResponse([
            'data' => new CarResource($car)
        ]);
    }

    /**
     * @throws UserAlreadyIsDrivingException
     */
    public function driveCar(Car $car, User $user): JsonResponse
    {
        $user->drive($car);

        return new JsonResponse([
            'message' => 'car was successfully given to this user'
        ]);
    }

    public function leaveCar(User $user): JsonResponse
    {
        $user->leaveCar();

        return new JsonResponse([
            'message' => 'user removed from car'
        ]);
    }
}
