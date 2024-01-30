<?php

namespace App\Http\Controllers;

use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\User;
use Exception;
use Faker\Core\Number;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    private $totalAmount;
    

    //fetching all invoice
    public function index(): JsonResponse
    {
        try {

            return response()->json([
                'ok' => true,
                'msg' => 'All invoices fetched successfully',
                'data' => InvoiceResource::collection(
                    Invoice::all()
                )
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


    //Store an invoice
    public function store(Request $request): JsonResponse
    {


        if (request()->header('Content-Type') !== 'application/json') {

            return response()->json([
                'ok' => false,
                'msg' => "Json request is expected. Header's content-type is application/json",

            ], Response::HTTP_BAD_REQUEST);
        }

        $validator  =  Validator::make(
            $request->all(),
            [
                'items' => ['array', 'required'],
                'items.*.item_code' => ['required', Rule::exists('items', 'item_id')],
                'items.*.quantity' => ['required', 'numeric'],
                'customer_id' =>  ['required', Rule::exists('users', 'id')],
                'issue_date' => ['required', 'date'],
                'due_date' => ['required', 'date'],

            ],
            [
                'items.*.item_code.exists' => "The item is invalid",
                'customer_id.exists' => 'The customer id is invalid',
                'customer_id.required' => 'The customer id is required',
            ]

        );

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'msg' => 'Creating an invoice failed',
                'error' => $validator->errors(),
            ]);
        }

        try {
            DB::beginTransaction();

            //Generating the invoice code based on the day
            $count = Invoice::whereDate('created_at', date('Y-m-d'))->count();
            $invoiceCode = 'INV-' . date('Y-m-') . Str::padLeft($count, 4, '0');
            $this->totalAmount = 0;

           $invoice = Invoice::create([
                "id" => $invoiceCode,
                "customer_id" => $request->input('customer_id'),
                'issue_date' => $request->date('issue_date'),
                'due_date' => $request->date('due_date'),
                "total_amount" => 0
            ]);

            //Storing each invoice item
            $request->collect('items')->each(function ($item)  use ($invoiceCode,) {
                $itemDetails = Item::find($item['item_code']);
                $subtotal = $item['quantity'] * $itemDetails->unit_price;
                $this->totalAmount = $this->totalAmount + $subtotal;

                InvoiceItem::create([
                    "invoice_id" => $invoiceCode,
                    'item_description' => $itemDetails->item_description,
                    'unit_price' => $itemDetails->unit_price,
                    'subtotal' => $subtotal,
                    'quantity' => $item['quantity'],
                ]);
            });



            $invoice->total_amount = $this->totalAmount;
            $invoice->save();

            
            DB::commit();

            return response()->json([
                'ok' => true,
                'msg' => 'Creating an invoice successful',
            ]);

        } catch (Exception $ex) {
            DB::rollBack();

            Log::error($ex->getMessage());
            return response()->json([
                'ok' => false,
                'msg' => 'An internal error occured. Please try again later',
                'error' => $ex->getMessage(),
            ],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Show invoice details for a particular
    public function show($invoice_id): JsonResponse
    {
        try {

            $invoice = Invoice::findOr($invoice_id, function () {

                return false;
            });

            if (!$invoice) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Invoice code is invalid'
                ]);
            }


            return response()->json([
                'ok' => true,
                'msg' => 'Invoice details fetched successfully',
                'data' => InvoiceResource::make($invoice)
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

    //Show all invoices under a particular customer
    public function showCustomerInvoices($customer_id,)
    {
        try {

            $customer = User::findOr($customer_id, function () {

                return false;
            });

            if (!$customer) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Customer code is invalid'
                ]);
            }

            return response()->json([
                'ok' => true,
                'msg' => 'Customer invoices fetched successfully',
                'data' => InvoiceResource::collection($customer->invoices)
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

    //delete
    public function destroy($invoice_id): JsonResponse
    {
        try {

            $invoice = Invoice::findOr($invoice_id, function () {

                return false;
            });

            if (!$invoice) {
                return response()->json([
                    'ok' => false,
                    'msg' => 'Invoice code is invalid'
                ]);
            }
            $invoice->delete();


            return response()->json([
                'ok' => true,
                'msg' => 'Invoice deleted successfully',
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


}
