<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\SMS;
use App\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $id = auth()->user()->id;
        $data['user'] = User::where('id', $id)->first();

        return view('profile.edit', $data);
    }

    public function show()
    {
        $id = auth()->user()->id;
        $data['user'] = User::where('id', $id)->first();

        return view('profile.show', $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'     => 'required|max:100',
            'address'  => 'string',
            'password' => 'nullable|confirmed|min:8',
        ]);
        
        if ($request->has('photo') && !empty($request->photo)) {
            $data['photo'] = base64_to_image($request->photo, 'upload/sp');
        }


        try{
            DB::beginTransaction();

            User::where('id', $id)->update([
                'name'     => $data['name']
            ]);
            if ($request->has('password') && $request->password!='' && $update_user) {
                User::where('id', $id)->update([
                    'password' => Hash::make($request->password)
                ]);
            }
            unset($data['password']);

            Admin::where('user_id', $id)->update($data);
            
            DB::commit();

            toastr()->success('Profile update successfully');
        }catch(\Exception $e){
            toastr()->error('Profile update failed');
            DB::rollback();
        }
        return redirect()->back();
    }
}
