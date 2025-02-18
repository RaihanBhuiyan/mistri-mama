<?php
 
namespace App\Http\Controllers\User;

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

class UserController extends Controller
{
    public function index()
    {
        $data['admins'] = Admin::orderBy('id', 'desc')->get();
        return view('user.index', $data);
    }

    public function create()
    {
        $roles = Role::pluck('name', 'id')->toArray();
        $array_flip = array_flip($roles);
        unset($array_flip['esp']);
        unset($array_flip['comrade']);
        unset($array_flip['fsp']);
        unset($array_flip['client']);
        unset($array_flip['b2bclient']);
        $data['roles'] = array_flip($array_flip);
        return view('user.create', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|max:100',
            'phone'    => 'numeric|required|unique:users,phone|digits:11|regex:/(01)[0-9]{9}/',
            'email'    => 'max:100|email|required|unique:users,email',
            'address'  => 'string',
            'password' => 'required|confirmed|min:8',
            'type'     => 'string|required',
            'remarks'  => 'string',
        ]);

        $data['photo'] = NULL;
        if ($request->has('photo')) {
            $data['photo'] = base64_to_image($request->photo, 'upload/sp');
        }

        try{
            DB::beginTransaction();

            $data['status'] = 1;
            $RegisteredUser = RegisterController::create($data, 1);
            $data['user_id'] = $RegisteredUser->id;
            $admin = Admin::create($data);
            $RegisteredUser->assignRole($data['type']);
            //event(new SmsEvenet($RegisteredUser->phone, "Your phone number varification code is " . $RegisteredUser->otp_code));

            DB::commit();

            toastr()->success('New adminstration user create successfully');
        }catch(\Exception $e){
            toastr()->error('New adminstration user create failed');
            DB::rollback();
        }
        return redirect()->back();
    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $data['user'] = User::where('id', $id)->first();
        
        $roles = Role::pluck('name', 'id')->toArray();
        $array_flip = array_flip($roles);
        unset($array_flip['esp']);
        unset($array_flip['comrade']);
        unset($array_flip['fsp']);
        unset($array_flip['client']);
        $data['roles'] = array_flip($array_flip);

        return view('user.edit', $data);
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

            toastr()->success('User update successfully');
        }catch(\Exception $e){
            toastr()->error('User update failed');
            DB::rollback();
        }
        return redirect()->back();
    }
}
