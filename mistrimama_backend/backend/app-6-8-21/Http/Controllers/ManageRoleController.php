<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ManageRoleController extends Controller
{
    public function index(Request $request)
    {
        $data['roles'] = Role::orderBy('id', 'desc')->get();
        return view('roles.index' , $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|unique:roles,name|max:55',
        ]);
        try{
            DB::beginTransaction();

            Role::create($data);

            DB::commit();

            toastr()->success('Role create successfully');
        }catch(\Exception $e){
            toastr()->error('Role create failed');
            DB::rollback();
        }
        return redirect()->back();
    }

    public function edit($id)
    {
        $data['role'] = Role::find($id);
        return view('roles.edit' , $data);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|max:55|unique:roles,name,'. $id,
        ]);
        try{
            DB::beginTransaction();

            Role::where('id', $id)->update($data);

            DB::commit();

            toastr()->success('Role update successfully');
        }catch(\Exception $e){
            toastr()->error('Role update failed');
            DB::rollback();
        }
        return redirect()->back();
    }
}
