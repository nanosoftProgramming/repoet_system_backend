<?php

namespace Modules\User\App\Http\Controllers\Api;

use Modules\User\DTO\UserDto;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Modules\User\Service\UserService;
use Modules\User\App\Http\Requests\UserLoginRequest;
use Modules\User\App\Http\Requests\UserRegisterRequest;
use Modules\User\App\Models\User;

class UserAuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(private UserService $userService)
    {
        $this->middleware('auth:user', ['except' => ['login']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            if (!$token = auth('user')->attempt($credentials)) {
                return returnValidationMessage(false, 'Unauthorized', ['password' => 'Wrong Credentials'], 'unauthorized');
            }
            $user = auth('user')->user();
            if ($user['is_active'] == 0) {
                return returnMessage(false, 'In-Active User Verification Required', null, 'temporary_redirect');
            }
            return $this->respondWithToken($token);
        } catch (\Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
public function me()
{
    $user = User::with('academy')
        ->find(auth('user')->id());

    return returnMessage(true, 'User Data', $user);
}

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return returnMessage(true, 'Successfully logged out.');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        $user = auth('user')->user();
        return returnMessage(true, 'Successfully Logged in.', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('user')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }
}
