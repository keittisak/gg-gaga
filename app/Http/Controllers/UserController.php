<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DataTables;
use DB;

class UserController extends Controller
{
    public function index (Request $request)
    {
        $data = [
            'title_en' => 'Users',
            'title_th' => 'ผู้ใช้งาน'
        ];
        return view('users.index',$data);
    }

    public function data (Request $request)
    {
        $users = User::all();
        return DataTables::of($users)
        ->addColumn('action', function($users) {
            return '<a href="'.route('users.edit', $users->id).'" class="btn btn-secondary btn-sm mr-2"><i class="far fa-edit"></i></a>
                    <button type="button" class="btn btn-secondary btn-sm btnDelete" data-id="'.$users->id.'"><i class="far fa-trash-alt"></i></button>';
        })
        ->make(true);
    }

    public function create (Request $request)
    {
        $data = [
            'action' => 'create',
            'title_en' => 'Add User',
            'title_th' => 'เพิ่มผู้ใช้งาน',
            'user' => new User
        ];
        return view('users.form', $data);
    }

    public function store (Request $request)
    {
        if (isset($request->user()->id)){
            $request->merge(array('created_by' => $request->user()->id));
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            'username' => [
                'required',
                'unique:users,username'
            ],
            'name' => [
                'required',
                'max:255'
            ],
            'password' => [
                'required',
                'confirmed',
                'min:6',
            ],
            'created_by' => [
                'nullable',
                'integer'
            ],
            'updated_by' => [
                'nullable',
                'integer'
            ],
        ];
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $user = DB::transaction(function() use($request, $data) {
            $data['password'] = \Hash::make($data['password']);
            $user = User::create($data);
            return $user;
        });
        return response($user,'201');
    }

    public function edit (Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = [
            'action' => 'update',
            'title_en' => 'Edit User',
            'title_th' => 'แก้ไขผู้ใช้งาน',
            'user' => $user
        ];
        return view('users.form', $data);
    }

    public function update (Request $request,$id)
    {
        $user = User::findOrFail($id);
        if (isset($request->user()->id)){
            $request->merge(array('updated_by' => $request->user()->id));
        }

        $validate = [
            'username' => [
                'required',
                'unique:users,username,'.$user->id
            ],
            'name' => [
                'required',
                'max:255'
            ],
            'created_by' => [
                'nullable',
                'integer'
            ],
            'updated_by' => [
                'nullable',
                'integer'
            ],
        ];
        if(!empty($request->password)){
            $validate['password'] = [
                'required',
                'confirmed',
                'min:6',
            ];
        }
        $request->validate($validate);
        $data = array_only($request->all(), array_keys($validate));
        $user = DB::transaction(function() use($request, $data, $user) {
            if(!empty($data['password'])){
                $data['password'] = \Hash::make($data['password']);
            }
            $user->update($data);
            return $user;
        });
        return response($user,'200');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        DB::transaction(function() use($user) {
            $user->delete();
        });
        return response('','204');
    }
}
