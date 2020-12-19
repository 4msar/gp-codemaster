<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::when($request->search, function($query)use($request){
            $query->where('customer_id', 'like', "%{$request->search}%");
            $query->where('booking_id', 'like', "%{$request->search}%");
        })->when($request->date, function($query)use($request){
            $query->whereDate('date', $request->date);
        })->get();
        return response()->json([
            'status' => true,
            'data' => $payments
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'booking_id' => 'required',
            'amount' => 'required',
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'all_errors' => $validator->errors()
            ], 423);
        }
        $booking = Booking::find($request->booking_id);
        $payment = Payment::create([
            'customer_id' => $request->customer_id,
            'booking_id' => $request->customer_id,
            'amount' => $request->amount,
            'due' => ($booking->room->price??0) - $request->amount,
            'date' => Carbon::now()
        ]);
        if( $payment ){
            return response()->json([
                'status' => true,
                'message' => "Payment Added Successfully!",
                'data' => $payment
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Payment Failed!",
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return response()->json([
            'status' => true,
            'data' => $payment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        if( $payment->delete()() ){
            return response()->json([
                'status' => true,
                'message' => "Delete Successfully!",
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Delete Failed!",
        ], 400);
    }
}
