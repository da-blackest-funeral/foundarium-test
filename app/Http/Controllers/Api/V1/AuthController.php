<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/login",
     *     summary="Retrieving Bearer Token",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 example={"email": "email@mail.com", "password": "12345679"}
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
     *                         property="token",
     *                         type="string",
     *                         description="Bearer auth token"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="message",
     *                         type="string",
     *                         description="unauth message"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (! \Auth::attempt($credentials)) {
            return new JsonResponse([
                'message' => __('auth.incorrect_credentials'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = User::whereEmail($request->email)
            ->first();

        return new JsonResponse([
            'token' => $user->createToken('api_key')->plainTextToken,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     summary="Logging out current user",
     *     tags={"User"},
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
     *                         description="logout message"
     *                     ),
     *                 ),
     *             ),
     *         },
     *     ),
     * ),
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        session()->flush();

        return new JsonResponse([
            'message' => __('auth.logout'),
        ]);
    }
}
