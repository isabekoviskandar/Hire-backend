<?php

namespace App\Interfaces\Http\Controllers\Auth;

use App\Application\Auth\Commands\LoginUserCommand;
use App\Application\Auth\Commands\RegisterUserCommand;
use App\Application\Auth\Handlers\LoginUserHandler;
use App\Application\Auth\Handlers\RegisterUserHandler;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private RegisterUserHandler $registerHandler,
        private LoginUserHandler $loginHandler,
    ) {}

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role' => 'sometimes|in:client,freelancer,both',
        ]);

        $user = $this->registerHandler->handle(
            new RegisterUserCommand(
                first_name: $request->first_name,
                last_name: $request->last_name,
                email: $request->email,
                password: $request->password,
                role: $request->role ?? 'client',
            )
        );

        $userModel = User::find($user->id());
        $token = $userModel?->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            'token' => $token,
            'user' => [
                'id' => $user->id(),
                'email' => $user->email()->value(),
                'role' => $user->role()->value,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = $this->loginHandler->handle(
            new LoginUserCommand(
                email: $request->email,
                password: $request->password,
            )
        );

        $userModel = User::find($user->id());
        $token = $userModel?->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id(),
                'email' => $user->email()->value(),
                'role' => $user->role()->value,
            ],
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Logged out']);
    }

    public function me(): JsonResponse
    {
        $user = auth()->user();

        return response()->json($user);
    }
}
