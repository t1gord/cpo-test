<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (Auth::attempt(credentials: $request->validated())) {
            if ($user = Auth::user()) {
                $token = $user->createToken(name: 'access_token')->plainTextToken;

                return new JsonResponse(
                    data: [
                        'message' => 'Logged in successfully',
                        'token' => $token
                    ],
                    status: Response::HTTP_OK
                );
            }
        }

        return new JsonResponse(
            data: [
                'message' => 'Invalid credentials',
            ],
            status: Response::HTTP_BAD_REQUEST
        );
    }
}
