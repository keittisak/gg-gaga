<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use DB;

class CustomerController extends Controller
{
    public function store (Request $request)
    {
        if (isset($request->user()->id)){
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            'full_name' => [
                'required',
            ],
            'address' => [
                'nullable'
            ],
            'full_address' =>[
                'required',
            ],
            'phone' => [
                'required',
                'unique:customers,phone'
            ],
            'subdistrict_id' => [
                'integer'
            ],
        ];

        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $customer = DB::transaction(function() use($request, $data) {
            $customer = new Customer();
            $customer = $customer->create($data);
            return $customer;
        });
        return $customer;
    }

    public function update (Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);
        if (isset($request->user()->id)){
            $request->merge(array('created_by' => $request->user()->id));
        }

        $validate = [
            'full_name' => [
                'required',
            ],
            'address' => [
                'nullable'
            ],
            'full_address' =>[
                'required',
            ],
            'phone' => [
                'required',
                'unique:customers,phone,'.$customer->id,
            ],
            'subdistrict_id' => [
                'integer'
            ],
        ];

        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        try{
            $customer = DB::transaction(function() use($request, $customer, $data) {
                $customer = $customer->update($data);
                return $customer;
            });
            return response($customer,'200');
        } catch (\Exception $e) {
            return response(['message' => $e->getMessage()],500);
        }
    }
    
    public function searchPhone (Request $request)
    {
        try{
            $customer = DB::transaction(function() use($request) {
                return Customer::where('phone', $request->phone)->first();
            });
            if($customer){
                return response($customer,'200');
            }
            return response(['id' => null],'200');
        }catch(\Exception $e) {
            return response($e->getMessage());
        }
    }
}
