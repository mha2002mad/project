<?php
namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Services\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class users extends Controller {
    protected UsersService $usersService;

    public function __construct(UsersService $usersService){
        $this->usersService = $usersService;
    }

    /**
     * issue your JWT token.
     * the token is required for other route to pass.
     * 
     * PLEASE COPY THE JWT TOKEN, AND PASS USE IT IN "Authorization" HEADER WITH THIS FORMAT: Bearer {token}
     *
     * @group register
     *
     * @bodyParam name string required name.
     * @bodyParam email email required email. ex:dd3d3dd3@provider.com
     * @bodyParam password password required the password must be at least 10 characters.
     * 
     */
    public function registerUser(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'string|required|min:10'
        ]);

        $validation->validate();

        try {
            $user = $this->usersService->createUser([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);
        } catch (\Throwable $th) {
            return response()->json(['messsage' => $th->getMessage()]);
        }

        $token = auth()->guard('api')->login($user) ?? false;

        return response()->json([
            'key' => $token,
        ]);
    }
}