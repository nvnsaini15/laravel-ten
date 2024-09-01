<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use App\Models\User;
use App\Models\Role;
use Response;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'email' => 'required|email|unique:users,email|max:100',
            'phone' => 'required|unique:users,phone|numeric|digits:10',
            'role_id' => 'required|exists:roles,id',
            'description' => 'required|max:500',
            'profile_image_file' => 'mimes:jpeg,jpg,png,gif|required|max:10000'

        ]);
        if ($validator->fails()) {
            return Response::json([$validator->errors(), 422]);
        }

        $data = $request->all();
        $imageName = uniqid().'.'.$request->profile_image_file->getClientOriginalExtension();
        FileUploadService::upload($imageName, $request->profile_image_file);
        $data['profile_image'] = $imageName;
        User::create($data);
        return Response::json([User::with('role')->get(), 200]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
