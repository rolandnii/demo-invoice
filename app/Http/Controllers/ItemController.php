<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index(): JsonResponse
    {
        try {

            return response()->json([
                'ok' => true,
                'msg' => 'Items fetched successfully',
                'data' => ItemResource::collection(
                    Item::all()
                )
            ]);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occured. Please try again later',
                'error' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make(
            $request->all(),
            [
                'description' => ['required', 'string', Rule::unique('items', 'item_description')],
                'unit_price' => ['required', 'numeric',],
                'total_quantity' => ['required', 'numeric'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Creating item failed',
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }


        try {

            Item::create(
                [
                    'item_description' => $request->description,
                    'unit_price' => $request->unit_price,
                    'total_quantity' => $request->total_quantity
                ]
            );

            return response()->json([
                'ok' => true,
                'msg' => 'New item created successfully'
            ]);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occured. Please try again later',
                'error' => $ex->getMessage(),
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($item_code): JsonResponse
    {
        try {

            $item = Item::findOr($item_code, function () {

                return false;
            });

            if (!$item) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Item code is invalid'
                ]);
            }


            return response()->json([
                'ok' => true,
                'msg' => 'Item details fetched successfully',
                'data' => new ItemResource(
                    $item
                )
            ]);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occured. Please try again later',
                'error' => $ex->getMessage(),
            ]);
        }
    }


    public function destroy($item_code): JsonResponse
    {
        try {

            $item = Item::findOr($item_code, function () {

                return false;
            });

            if (!$item) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Item code is invalid'
                ]);
            }

            $item->delete();

            return response()->json([
                'ok' => true,
                'msg' => 'Item deleted successfully',
            ]);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occured. Please try again later',
                'error' => $ex->getMessage(),
            ]);
        }
    }

    public function update($item_code, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'description' => ['required', 'string', Rule::unique('items', 'item_description')->ignore($item_code,'item_id')],
                'unit_price' => ['required', 'numeric',],
                'total_quantity' => ['required', 'numeric'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Updating item failed',
                'error' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        try {

            $item = Item::findOr($item_code, function () {

                return false;
            });

            if (!$item) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Item code is invalid'
                ]);
            }

            $item->update([
                'item_description' => $request->input('description'),
                'total_quantity' => $request->input('total_quantity'),
                'unit_price' => $request->input('unit_price'),
            ]);

            return response()->json([
                'ok' => true,
                'msg' => 'Item updated successfully',
            ]);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());

            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occured. Please try again later',
                'error' => $ex->getMessage(),
            ]);
        }
    }
}
