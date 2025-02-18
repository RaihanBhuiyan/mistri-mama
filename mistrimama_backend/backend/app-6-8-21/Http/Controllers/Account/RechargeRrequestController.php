<?php

namespace App\Http\Controllers\Account;

use App\Account;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\NotificationController;
use App\RechargeRequest;
use App\User;
use Carbon\Carbon;
use App\SMS;

class RechargeRrequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['rechargeRequests'] = RechargeRequest::where('status', 0)->orderBy('id', 'desc')->get();
        return view('account.recharge.index', $data);
    }

    public function history()
    {
        $data['rechargeRequests'] = RechargeRequest::where('status', '!=', 0)->orderBy('id', 'desc')->get();
        return view('account.recharge.history', $data);
    }
    
    public function store(Request $request)
    {
        if($request->medium == 'bkash')
        {
            $request->validate([
                'medium' => 'required',
                'trxno' => 'required',
                'amount' => 'required|numeric',
            ]);
            $trxno = $request->trxno;
        }
        if($request->medium == 'Bank Deposit')
        {
            $request->validate([
                'medium' => 'required',
                'branch_number' => 'required',
                'serial_number' => 'required',
                'date' => 'required|date_format:Y-m-d',
                'amount' => 'required|numeric',
            ]);
            $trxno = $request->branch_number.'/'.$request->serial_number.'/'.$request->date;
        }
        if($request->medium == 'Mistrimama Agent')
        {
            $request->validate([
                'medium' => 'required',
                'memo_number' => 'required',
                'agent_id_number' => 'required',
                'amount' => 'required|numeric',
            ]);
            $trxno = $request->memo_number.'/'.$request->agent_id_number;
        }

        if(RechargeRequest::where(['trxno' => $trxno])->exists())
        {
            return response()->json(['message' => 'This transaction no already used'], 400);
        }

        try{
            DB::beginTransaction();
            $auth_user_id = auth()->user()->id;

            $data['user_id'] = $auth_user_id;
            $data['medium'] = $request->medium;
            $data['trxno'] = $trxno;
            $data['amount'] = $request->amount;

            $recharge_request = RechargeRequest::create($data);
            
            NotificationController::rechargeRequestNotification($data);
            DB::commit();

            return response()->json(['message' => 'Recharge request placed successfully'], 200);

        }catch(\Exception $e){
            DB::rollback();
        }
        return response()->json(['message' => 'Recharge request placed failed'], 400);
    }
    
    public function rechargeApprove($id)
    {
        $recharge_request = RechargeRequest::find($id);
        
        try{
            DB::beginTransaction();

            $recharge_request->status = 1;
            $recharge_request->approve_by = auth()->user()->name;
            $recharge_request->approve_time = Carbon::now()->toDateTimeString();
            $recharge_request->save();

            $user = User::find($recharge_request->user_id);
            $sp = $user->serviceProvider;
            
            $data['trx_for'] = 'service_provider';
            $data['user_id'] = $recharge_request->user_id;
            $data['amount'] = $recharge_request->amount;
            $data['trxno'] = $recharge_request->trxno;
            $data['type'] = 'Cash';
            $data['heading_type'] = 'revenue';
            $data['heading'] = 'Service provider account recharge';
            $data['details'] = $sp->name . ' (' . $sp->sp_code . ') recharge '.$recharge_request->amount.' BDT';
            $data['ref'] = 'recharge';
            $data['ref_key'] = $recharge_request->id;
            $data['status'] = 'credit';
            $data['date'] = Carbon::now()->toDateString();
            
            Account::create($data);
            NotificationController::rechargeRequestApproveNotification($user);
            SMS::send($sp->phone, "Your account successfully recharged with BDT ".$recharge_request->amount."/-. Your current online balance is BDT ".$sp->balance."/-.");
            DB::commit();

            toastr()->success('Recharge request approve successfully');

        }catch(\Exception $e){
            DB::rollback();
            toastr()->error('Recharge request approve failed');
        }
        return redirect()->back();
    }


    public function rechargeDeny($id)
    {
        $recharge_request = RechargeRequest::find($id);

        try{
            DB::beginTransaction();

            $recharge_request->status = 2;
            $recharge_request->approve_by = auth()->user()->name;
            $recharge_request->approve_time = Carbon::now()->toDateTimeString();
            $recharge_request->save();

            DB::commit();

            toastr()->success('Recharge request denied successfully');

        }catch(\Exception $e){
            DB::rollback();
            toastr()->error('Recharge request denied failed');
        }
        return redirect()->back();
    }

    public function rechargeExport()
    {
        $recharge_request = RechargeRequest::with('user:id,name,phone')->where(['status' => 0])->orderBy('id', 'desc')->get();

        if (empty($recharge_request)) {
            toastr()->error('Recharge request not available');
            return redirect()->back();
        }

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=recharge_request_".date('d-m-Y').".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = ['id', 'name', 'phone', 'trxno', 'amount', 'status', 'request_at'];

        $callback = function () use ($recharge_request, $columns) {
            $file = fopen('php://output', 'w');;
            fputcsv($file, $columns);

            foreach ($recharge_request as $item) {
                fputcsv($file, [$item->id, $item->user->name, $item->user->phone, $item->trxno, $item->amount.' Tk', $item->status, $item->created_at]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
    
    public function rechargeImport(Request $request)
    {
        $destination_path = public_path('upload');

        if ($request->hasFile('recharge_request_file')) {
            $file = $request->file('recharge_request_file');
            $file->move($destination_path, $file->getClientOriginalName());

            $csvFile = $destination_path.'/'.$file->getClientOriginalName();

            $fileArray = csvToArray($csvFile);
            if (!empty($fileArray)) {
                foreach ($fileArray as $key => $value) {
                    if($value['status'] == 1)
                    {
                        $this->rechargeApprove($value['id']);
                    }
                    if($value['status'] == 2)
                    {
                        $this->rechargeDeny($value['id']);
                    }
                }
            }
            else{
                toastr()->error('File has no formated data');
            }
        }
        return redirect()->back();
    }
}
