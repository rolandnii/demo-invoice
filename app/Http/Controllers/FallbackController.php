<?php

namespace App\Http\Controllers;

use Faker\Core\Number;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'msg' => 'endpoint not found'
        ], Response::HTTP_NOT_FOUND);
    }

}
