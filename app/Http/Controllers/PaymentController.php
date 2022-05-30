<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Payment;
use App\Libraries\Image;

class PaymentController extends Controller
{
    public function store (Request $request)
    {
        if (isset($request->user()->id)){
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'method_id' => [
                'nullable',
                'integer',
                // 'exists:payment_methods,id'
            ],
            'bank_id' => [
                'nullable',
                'integer',
                // 'exists:banks,id'
            ],
            'statement_id' => [
                'nullable',
                'integer',
                // 'exists:statements,id'
            ],
            'amount' => [
                // 'numeric',
                // 'min:0'
            ],
            'transfered_at' => [
                'date_format:Y-m-d H:i:s'

            ],
            'image' => [
                'nullable',
            ],
            'status' => [
                'in:waiting,confirm,reject'
            ],
            'created_by' => [
                'nullable',
                'integer'
            ],
            'updated_by' => [
                'nullable',
                'integer'
            ],
            'created_at' => [
                
            ]

        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $payment = DB::transaction(function() use($request, $data) {
            $payment = new Payment();
            return $payment->create($data);
        });
        return $payment;
    }

    public function update (Request $request, int $id)
    {
        $payment = Payment::findOrFail($id);
        if (isset($request->user()->id)){
            $request->merge(array('updated_by' => $request->user()->id));
        }
        $validate = [
            'method_id' => [
                'nullable',
                'integer',
                // 'exists:payment_methods,id'
            ],
            'bank_id' => [
                'nullable',
                'integer',
                // 'exists:banks,id'
            ],
            'statement_id' => [
                'nullable',
                'integer',
                // 'exists:statements,id'
            ],
            'amount' => [
                // 'numeric',
                // 'min:0'
            ],
            'transfered_at' => [
                'date_format:Y-m-d H:i:s'

            ],
            'image' => [
                'nullable',
            ],
            'status' => [
                'in:waiting,confirm,reject'
            ],
            'created_by' => [
                'nullable',
                'integer'
            ],
            'updated_by' => [
                'nullable',
                'integer'
            ],
            'created_at' => [
                
            ]

        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $payment = DB::transaction(function() use($request, $payment, $data, $id) {
            return $payment->update($data);
        });
        return $payment;
    }
}
