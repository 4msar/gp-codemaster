<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Resources\BookingResponse;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Response the all bookings 
     * @return  \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $bookings = Booking::when($request->search, function($query)use($request){
            $query->where('room_number', 'like', "%{$request->search}%");
        })->with('payment')->get();
        return BookingResponse::collection($bookings)->additional([ 'status' => true ]);
    }

    /**
     * Handle the Booking Request
     * @param  Request $request
     * @return mixed|json
     */
    public function book(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required',
            'room_number' => 'required',
            'customer_id' => 'required',
            'arrival' => 'nullable|date|date_format:Y-m-d H:i:s', 
            'checkout' => 'nullable|date|date_format:Y-m-d H:i:s', 
            'book_type' => 'required', 
            'book_time' => 'nullable|date|date_format:Y-m-d H:i:s',
            'amount' => 'sometimes|required'
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'all_errors' => $validator->errors()
            ], 423);
        }
        $room = Room::find($request->room_id);
        if( $room->isLocked() ){
            return response()->json([
                'status' => false,
                'message' => "The room is unavailable!",
            ], 423);
        }
        $booking = Booking::create([
            'room_id' => $request->room_id,
            'room_number' => $request->room_number,
            'customer_id' => $request->customer_id,
            'arrival' => $request->arrival, 
            'checkout' => $request->checkout, 
            'book_type' => $request->book_type, 
            'book_time' => Carbon::now()
        ]);
        if( $request->amount ){
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'customer_id' => $booking->customer_id,
                'amount' => $request->amount,
                'due' =>  $room->price - $request->amount,
                'date' => Carbon::now()
            ]);
        }

        if( $booking ){
            $room->fill(['locked' => true])->save();
            return response()->json([
                'status' => true,
                'message' => "Added Successfully!",
                'data' => $booking
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Added Failed!",
        ], 400);
    }

    /**
     * Handle the Booking Update Request
     * @param  Request $request
     * @return mixed|json
     */
    public function update(Request $request, $booking)
    {
        $booking = Booking::findOrFail($booking);
        $validator = Validator::make($request->all(), [
            'room_id' => 'nullable',
            'room_number' => 'required_with:room_id',
            'arrival' => 'required|date', 
            'checkout' => 'nullable|date', 
            'book_type' => 'required', 
            'book_time' => 'nullable|date'
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'all_errors' => $validator->errors()
            ], 423);
        }
        $room = Room::find($booking->room_id);
        if( $request->room_id !== $booking->room_id ){
            $room->fill(['locked' => false])->save();
            $room = Room::find($request->room_id);
        }
        $booking->fill([
            'room_id' => $room->id,
            'room_number' => $room->room_number,
            'arrival' => $request->arrival ?? $booking->arrival, 
            'checkout' => $request->checkout ?? $booking->checkout, 
            'book_type' => $request->book_type ?? $booking->book_type, 
        ]);

        if( $booking->save() ){
            $room->fill(['locked' => true])->save();
            return response()->json([
                'status' => true,
                'message' => "Update Successfully!",
                'data' => $booking
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Update Failed!",
        ], 400);
    }

    /**
     * Handle the Booking Check In
     * @param  Request $request
     * @return mixed|json
     */
    public function checkIn(Request $request, $booking)
    {
        $validator = Validator::make($request->all(), [
            'arrival' => 'required|date|date_format:Y-m-d H:i:s', 
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'all_errors' => $validator->errors()
            ], 423);
        }
        $booking = Booking::findOrFail($booking);

        $booking->fill([
            'arrival' => $request->arrival, 
        ]);

        if( $booking->save() ){
            return response()->json([
                'status' => true,
                'message' => "Checked In Successfully!",
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Checked In Failed!",
        ], 400);
    }

    /**
     * Handle the Booking Check Out
     * @param  Request $request
     * @return mixed|json
     */
    public function checkOut(Request $request, $booking)
    {
        $validator = Validator::make($request->all(), [
            'checkout' => 'required|date|date_format:Y-m-d H:i:s', 
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'all_errors' => $validator->errors()
            ], 423);
        }
        $booking = Booking::findOrFail($booking);

        $booking->fill([
            'checkout' => $request->checkout, 
        ]);

        if( $booking->save() ){
            return response()->json([
                'status' => true,
                'message' => "Checked Out Successfully!",
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Checked Out Failed!",
        ], 400);
    }
}
