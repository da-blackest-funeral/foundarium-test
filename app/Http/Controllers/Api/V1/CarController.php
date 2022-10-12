<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\CarIsBusyException;
use App\Exceptions\UserAlreadyIsDrivingException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class CarController extends Controller
{
    /**
     * @OA\Get(
     *     path="/cars/",
     *     summary="Show paginated cars",
     *     tags={"Cars"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                             ),@OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                             ),@OA\Property(
     *                                 property="driver",
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer"),
     *                                 @OA\Property(property="name", type="string"),
     *                                 @OA\Property(property="email", type="string"),
     *                             ),
     *                         )
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     */
    public function index(): LengthAwarePaginator
    {
        return tap(Car::with('users')
            ->paginate())
            ->transform(function (Car $car) {
                return new CarResource($car);
            });
    }

    /**
     * @OA\Get(
     *     path="/cars/{car}",
     *     summary="Show car",
     *     tags={"Cars"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                             ),@OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                             ),@OA\Property(
     *                                 property="driver",
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer"),
     *                                 @OA\Property(property="name", type="string"),
     *                                 @OA\Property(property="email", type="string"),
     *                             ),
     *                         )
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     */
    public function show(Car $car): CarResource
    {
        return new CarResource($car);
    }

    /**
     * @OA\Post(
     *     path="/cars",
     *     summary="Create new car",
     *     tags={"Cars"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                             ),@OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                             ),@OA\Property(
     *                                 property="driver",
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer"),
     *                                 @OA\Property(property="name", type="string"),
     *                                 @OA\Property(property="email", type="string"),
     *                             ),
     *                         )
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="If name of car not presented",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     */
    public function store(CarRequest $request): JsonResponse
    {
        $car = new Car();
        $car->name = $request->name;
        $car->save();

        return new JsonResponse([
            'data' => new CarResource($car)
        ]);
    }

    /**
     * @OA\Put(
     *     path="/cars/{car}",
     *     summary="Update car",
     *     tags={"Cars"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="data",
     *                         type="array",
     *                         @OA\Items(
     *                             @OA\Property(
     *                                 property="id",
     *                                 type="integer",
     *                             ),@OA\Property(
     *                                 property="name",
     *                                 type="string",
     *                             ),@OA\Property(
     *                                 property="driver",
     *                                 type="object",
     *                                 @OA\Property(property="id", type="integer"),
     *                                 @OA\Property(property="name", type="string"),
     *                                 @OA\Property(property="email", type="string"),
     *                             ),
     *                         )
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="If name of car not presented",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     */
    public function update(Car $car, CarRequest $request): JsonResponse
    {
        $car->name = $request->name;
        $car->save();

        return new JsonResponse([
            'data' => new CarResource($car)
        ]);
    }

    /**
     * @OA\Post(
     *     path="/users/{user}/cars/{car}",
     *     summary="Assign Car to user to drive",
     *     tags={"Driving"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="If car already been taken or user driving another car",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="exception message"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     *
     * @throws UserAlreadyIsDrivingException|CarIsBusyException
     */
    public function driveCar(User $user, Car $car): JsonResponse
    {
        $user->drive($car);

        return new JsonResponse([
            'message' => 'car was successfully given to this user'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/users/{user}/cars/",
     *     summary="Make user to leave car that he is driving now",
     *     tags={"Driving"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * )
     */
    public function leaveCar(User $user): JsonResponse
    {
        $user->leaveCar();

        return new JsonResponse([
            'message' => 'user removed from car'
        ]);
    }
}
