<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email', Rule::exists('users')],
                'password' => ['required', 'string'],
            ],
            [
                'email.exists' => 'Wrong login credentials'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Login failed',
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $user = User::firstWhere('email',$request->email);

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Wrong login credentials',
                ], Response::HTTP_BAD_REQUEST);
            }


            return response()->json([
                'ok' => true,
                'msg' => 'Login successful',
                'data' => UserResource::make($user),

            ], Response::HTTP_OK);

        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occured. Please try again later',
                'error' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required','string', 'regex:/^[\pL\s\-]+$/u'],
                'email' => ['required', 'email', Rule::unique('users')],
                'password' => ['required', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Login failed',
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' =>$request->password,
            ]);


            return response()->json([
                'ok' => true,
                'msg' => 'Customer account created successfully',

            ], Response::HTTP_OK);

        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occured. Please try again later',
                'error' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function adminRegister(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required','string', 'regex:/^[\pL\s\-]+$/u'],
                'email' => ['required', 'email', Rule::unique('users')],
                'password' => ['required', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Login failed',
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' =>$request->password,
                'usertype' => 'admin'
            ]);


            return response()->json([
                'ok' => true,
                'msg' => 'Admin account created successfully',

            ], Response::HTTP_OK);

        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occured. Please try again later',
                'error' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
