<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rooms = Room::when($request->search, function($query)use($request){
            $query->where('room_number', 'like', "%{$request->search}%");
        })->when(!$request->with_locked, function($query){
            $query->where('locked', false);
        })->get();
        return RoomResponse::collection($rooms)->additional([ 'status' => true ]);
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
            'room_number' => 'required|string|max:191',
            'price' => 'required|integer',
            'max_persons' => 'required',
            'room_type' => 'required',
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'all_errors' => $validator->errors()
            ], 423);
        }
        $room = Room::create([
            'room_number' => $request->room_number,
            'price' => $request->price,
            'locked' => false,
            'max_persons' => $request->max_persons,
            'room_type' => $request->room_type,
        ]);
        if( $room ){
            return response()->json([
                'status' => true,
                'message' => "Added Successfully!",
                'data' => $room
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Added Failed!",
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        return (new RoomResponse($room))->additional([ 'status' => true ]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'room_number' => 'required|string|max:191',
            'price' => 'required|integer',
            'max_persons' => 'required',
            'room_type' => 'required',
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'all_errors' => $validator->errors()
            ], 423);
        }
        $room->fill([
            'room_number' => $request->room_number,
            'price' => $request->price,
            'locked' => $request->lock ?? false,
            'max_persons' => $request->max_persons,
            'room_type' => $request->room_type,
        ]);
        if( $room ){
            return response()->json([
                'status' => true,
                'message' => "Update Successfully!",
                'data' => $room
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Update Failed!",
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        if( $room->delete() ){
            return response()->json([
                'status' => true,
                'message' => "Delete Successfully!",
                'data' => $room
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Delete Failed!",
        ], 400);
    }
}
