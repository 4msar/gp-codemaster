<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\CustomerResponse;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customers = Customer::when($request->search, function($query)use($request){
            $query->where('first_name', 'like', "%{$request->search}%");
            $query->where('last_name', 'like', "%{$request->search}%");
            $query->orWhere('email', 'like', "%{$request->search}%");
            $query->orWhere('phone', 'like', "%{$request->search}%");
        })->get();
        return CustomerResponse::collection($customers)->additional([ 'status' => true ]);
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
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email|unique:customers',
            'password' => 'required|string|min:8',
            'phone' => 'required|unique:customers',
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'all_errors' => $validator->errors()
            ], 423);
        }
        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'registered_at' => Carbon::now()
        ]);
        if( $customer ){
            return response()->json([
                'status' => true,
                'message' => "Added Successfully!",
                'data' => $customer
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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($customer)
    {
        $customer = Customer::findOrFail($customer);
        return (new CustomerResponse($customer))->additional(['status'=>true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $customer)
    {
        $customer = Customer::findOrFail($customer);
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:191',
            'last_name' => 'sometimes|required|string|max:191',
            'email' => "sometimes|required|email|unique:users,email,{$customer->id}",
            'password' => 'sometimes|required|string|min:8',
            'phone' => "sometimes|required|unique:users,phone,{$customer->id}",
        ]);
        if ( $validator->fails() ) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'all_errors' => $validator->errors()
            ], 423);
        }
        $customer->fill([
            'first_name' => $request->first_name ?? $customer->first_name,
            'last_name' => $request->last_name ?? $customer->last_name,
            'email' => $request->email ?? $customer->email,
            'phone' => $request->phone ?? $customer->phone,
        ]);
        if( $customer->save() ){
            return response()->json([
                'status' => true,
                'message' => "Update Successfully!",
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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($customer)
    {
        $customer = Customer::findOrFail($customer);
        if( $customer->delete()() ){
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
