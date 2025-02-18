<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Account\AccountController;
use App\WithdrawRequest;
use App\Http\Resources\UserCashoutHistoryResource;
use App\Http\Controllers\NotificationController;
use App\User;
use App\Account;
use App\RewardPoint;
use App\Http\Controllers\Client\RewardPointController;
use Carbon\Carbon;
use Hash;
use App\SMS;

class WithdrawRequestController extends Controller
{


    public function withdrawRequest()
    {
        $getRoleNames = auth()->user()->getRoleNames()->first();
        if($getRoleNames=='admin'){
            $data['withdraw_requests'] = WithdrawRequest::where(['status' => 0, 'type' => 'withdraw'])->orderBy('id', 'desc')->get();
        }else{
            $data['withdraw_requests'] = WithdrawRequest::where(['approve' => 1,'status' => 0, 'type' => 'withdraw'])->orderBy('id', 'desc')->get();
        }
        $data['getRoleNames'] =$getRoleNames;
        return view('account.withdraw.index', $data);
    }


    public function adminapprove($id)
    {
        $withdraw_request = WithdrawRequest::find($id);
        $withdraw_request->approve = 1;
        try{
            DB::beginTransaction();
            $withdraw_request->save();
            NotificationController::cashoutAcceptNotification($withdraw_request->type);
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->back();
    }
    
    public function withdrawHistory()
    {
        $data['withdraw_requests'] = WithdrawRequest::where('status', '!=', 0)->where(['type' => 'withdraw'])->orderBy('id', 'desc')->get();
        return view('account.withdraw.history', $data);
    }

    public function cashOutRequest()
    {
        $getRoleNames = auth()->user()->getRoleNames()->first();
        if($getRoleNames=='admin'){
            $data['withdraw_requests'] = WithdrawRequest::with('client')->where(['status' => 0, 'type' => 'cash_out'])->orderBy('id', 'desc')->get();
        }else{
            $data['withdraw_requests'] = WithdrawRequest::with('client')->where(['approve' => 1,'status' => 0, 'type' => 'cash_out'])->orderBy('id', 'desc')->get();
        }
        $data['getRoleNames'] = $getRoleNames;
        return view('account.cashout.index', $data);
    }

    public function cashOutHistory()
    {
        $data['withdraw_requests'] = WithdrawRequest::with('client')->where('status', '!=', 0)->where(['type' => 'cash_out'])->orderBy('id', 'desc')->get();
        return view('account.cashout.history', $data);
    }

    public function userCashOutHistory()
    {
        $user_id = auth()->user()->id;
        $cashout_history = WithdrawRequest::with('accountCashOut')->where(['user_id' => $user_id, 'type' => 'cash_out'])->orderBy('id', 'desc')->get();
        return UserCashoutHistoryResource::collection($cashout_history);
    }

    public function exportCashOutHistory()
    {
        $user_id = auth()->user()->id;
        $cashout_history = WithdrawRequest::with('accountCashOut')->where(['user_id' => $user_id, 'type' => 'cash_out'])->orderBy('id', 'desc')->get();
        if (empty($cashout_history))
        {
            return response()->json(['message' => 'You have no cashout history'], 400);
        }

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=Comrade_" . date('d-m-Y') . ".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $columns = [
            'Sl',
            'Request Date',
            'Receive Date',
            'Receive MFS Number',
            'Transaction ID',
            'Amount',
            'Balance',
        ];

        $callback = function () use ($cashout_history, $columns)
        {
            $file = fopen('php://output', 'w');;
            fputcsv($file, $columns);

            foreach ($cashout_history as $key => $item)
            {
                fputcsv($file, [
                    $key + 1,
                    $item->created_at,
                    $item->accountCashOut->receive_date,
                    $item->mfs_number,
                    $item->accountCashOut->trxno,
                    $item->accountCashOut->amount,
                    $item->client->available_reward_balance,
                ]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
    
    public function create(Request $request)
    {
        $data = $request->validate([
            'mfs' => 'required',
            'mfs_number' => 'required|numeric',
            'amount' => 'required|numeric|min:0',
            'remarks' => 'string|nullable',
        ]);

        $data['user_id'] = auth()->user()->id;
        $getRoleNames = auth()->user()->getRoleNames()->first();

        if($data['amount'] == 0)
        {
            return response()->json(['message' => 'You can not request zero balance'], 400);
        }
        
        if (Hash::check($request->password, auth()->user()->password)) {
            
            if($getRoleNames == 'client')
            {
                $rp = new RewardPoint();
                $reward_point = $rp->where(['user_id' => auth()->user()->id])->first();
                if(!empty($reward_point))
                {
                    if($reward_point->available_reward_balance >= 200)
                    {
                        RewardPointController::redeemPoint($data['amount']);
                        $heading = ucfirst($getRoleNames).' cash out request';
                        $details = 'Your cash out request has been placed';
                        $ref = 'cash_out';
                        $data['type'] = $ref;
                        $data['remarks'] = $details;
                        try{
                            DB::beginTransaction();
                            
                            $data['amount'] = round(($data['amount']/3), 2);
                            $withdrawRequest = WithdrawRequest::create($data);

                            $data['trx_for'] = 'client';
                            $data['user_id'] = $withdrawRequest->user_id;
                            $data['amount'] = $withdrawRequest->amount;
                            $data['heading'] = $heading;
                            $data['details'] = $details;
                            $data['ref'] = $ref;
                            $data['ref_key'] = $withdrawRequest->id;

                            $account = AccountController::WithdrawRequestDebit($data);
                            NotificationController::cashoutRequestNotification($data);
                            DB::commit();
            
                            return response()->json(['message' => 'Request Placed Successfully'], 200);
            
                        }catch(\Exception $e){
                            DB::rollback();
                            return response()->json(['message' => 'Request Placed failed'], 400);
                        }
                    }
                    return response()->json(['message' => 'You can not withdraw amount less then 200'], 400);
                }
                return response()->json(['message' => 'Not Available redeem point'], 400);
            }

            
            if($getRoleNames == 'esp' || $getRoleNames == 'fsp')
            {
                $withdrawable_balance = auth()->user()->serviceProvider->withdrawable_balance;
                $heading = ucfirst($getRoleNames).' withdraw request';
                $details = 'Your withdraw request has been placed';
                $ref = 'withdraw';
                $data['type'] = $ref;

                $data['remarks'] = $details;

                if(($data['amount'] > $withdrawable_balance))
                {
                    return response()->json(['message' => 'You have not enough balance'], 400);
                }

                try{
                    DB::beginTransaction();

                    $withdrawRequest = WithdrawRequest::create($data);

                    $data['trx_for'] = 'service_provider';
                    $data['user_id'] = $withdrawRequest->user_id;
                    $data['amount'] = $withdrawRequest->amount;
                    $data['heading'] = $heading;
                    $data['details'] = $details;
                    $data['ref'] = $ref;
                    $data['ref_key'] = $withdrawRequest->id;

                    $account = AccountController::WithdrawRequestDebit($data);

                    NotificationController::cashoutRequestNotification($data);
                    DB::commit();

                    return response()->json(['message' => 'Request Placed Successfully'], 200);

                }catch(\Exception $e){
                    DB::rollback();
                    return response()->json(['message' => 'Request Placed failed'], 400);
                }
            }

        } else {
            return response()->json(['message' => 'Your given password was wrong. Please make sure your password'], 400);
        }
    }

    public function aprrove(Request $request, $id)
    {
        if($request->transaction_no)
        {
            if(Account::where(['trxno' => $request->transaction_no])->exists())
            {
                toastr()->error('This transaction no already used');
                return redirect()->back();
            }
            $this->withdrawAprrove($id, $request->transaction_no);
        }
        else
        {
            toastr()->error('Please enter valid transaction no');
        }
        return redirect()->back();
    }

    public function deny($id)
    {
        $this->withdrawDeny($id);
        return redirect()->back();
    }

    public function withdrawAprrove($id, $trxNo)
    {
        $withdraw_request = WithdrawRequest::find($id);
        
        $user = User::find($withdraw_request->user_id);
        $getRoleNames = $user->getRoleNames()->first();

        if(Account::where(['trxno' => $trxNo])->exists())
        {
            toastr()->error('This transaction no already used');
            return false;
        }

        $current_balance = 0;
        try{
            DB::beginTransaction();
            
            if($getRoleNames == 'client')
            {
                $data['details'] = $user->name . ' cash out '.$withdraw_request->amount.' BDT.';
                $ref = 'cash_out';
                $current_balance = $user->client->available_reward_balance;

                $requestDebit['trx_for'] = 'mistrimama';
                $requestDebit['user_id'] = auth()->user()->id;
                $requestDebit['amount'] = $withdraw_request->amount;
                $requestDebit['heading'] = 'Reward point cash out request';
                $requestDebit['details'] = $data['details'];
                $requestDebit['ref'] = $ref;
                $requestDebit['ref_key'] = $withdraw_request->id;

                $account = AccountController::WithdrawRequestDebit($requestDebit);
            }

            if($getRoleNames == 'esp' || $getRoleNames == 'fsp')
            {
                $data['details'] = $user->name . ' (' . $user->serviceProvider->sp_code . ') withdraw '.$withdraw_request->amount.' BDT.';
                $ref = 'withdraw';
                $current_balance = $user->serviceProvider->balance;
            }
            $data['trxno'] = $trxNo;
            
            $withdraw_request->status = 1;
            $withdraw_request->approve = 1;
            $withdraw_request->remarks = $data['details'];

            $withdraw_request->save();
            Account::where(['ref_key' => $id, 'ref' => $ref])->update($data);

            NotificationController::cashoutApproveNotification($ref, $user);
            SMS::send($user->phone, "Cash out request has been approved. BDT ".$withdraw_request->amount."/- paid to your ".$withdraw_request->mfs." (".$withdraw_request->mfs_number.") from Mistri Mama. Your current online balance: BDT ".$current_balance."/-.");

            DB::commit();

            toastr()->success('Request approve successfully');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
            toastr()->error('Request approve failed');
        }
        return redirect()->back();
    }

    public function withdrawDeny($id)
    {
        $withdraw_request = WithdrawRequest::find($id);
        
        $user = User::find($withdraw_request->user_id);
        $getRoleNames = $user->getRoleNames()->first();
        
        try{
            DB::beginTransaction();

            if($getRoleNames == 'client')
            {
                $heading = 'Cash out request deny';
                $details = $user->name . ' cash out request has been denied. Reward point adjusted';
                $data['ref'] = 'cash_out';

                $rp = new RewardPoint();
                $rp->user_id = $user->id;
                $rp->rp = round(($withdraw_request->amount * 3));
                $rp->status = 'add';
                $rp->details = $details;
                $rp->save();
            }

            if($getRoleNames == 'esp' || $getRoleNames == 'fsp')
            {
                $heading = 'Withdraw request deny';
                $details = $user->name . ' withdraw request ' . '(' . $user->serviceProvider->sp_code . ') has been denied. Withdraw amount adjusted';
                $data['ref'] = 'withdraw';
                $data['trx_for'] = 'service_provider';
            }

            $withdraw_request->remarks = $details;
            $withdraw_request->status = 2;
            $withdraw_request->approve = 2;
            $withdraw_request->save();

            $data['user_id'] = $withdraw_request->user_id;
            $data['amount'] = $withdraw_request->amount;
            $data['heading'] = $heading;
            $data['details'] = $details;
            $data['ref_key'] = $withdraw_request->id;
            $account = AccountController::WithdrawRequestCredit($data);

            DB::commit();

            toastr()->success('Request denied successfully');

        }catch(\Exception $e){
            DB::rollback();
            toastr()->success('Request denied failed');
        }
        return redirect()->back();
    }

    public function withdrawExport()
    {
        $withdraw_request = WithdrawRequest::with('user:id,name,phone')->where(['approve' => 1,'status' => 0, 'type' => 'withdraw'])->orderBy('id', 'desc')->get();

        if (empty($withdraw_request)) {
            toastr()->error('Recharge request not available');
            return redirect()->back();
        }

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=withdraw_request_".time().".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = ['id', 'name', 'phone', 'trxno', 'amount', 'validations', 'request_at'];

        $callback = function () use ($withdraw_request, $columns) {
            $file = fopen('php://output', 'w');;
            fputcsv($file, $columns);

            foreach ($withdraw_request as $item) {
                fputcsv($file, [$item->id, $item->user->name, $item->mfs_number, $item->trxno, $item->amount.' Tk', NULL, $item->created_at]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
    
    public function withdrawImport(Request $request)
    {
        $destination_path = public_path('upload');

        if ($request->hasFile('withdraw_request_file')) {
            $file = $request->file('withdraw_request_file');
            $file->move($destination_path, $file->getClientOriginalName());

            $csvFile = $destination_path.'/'.$file->getClientOriginalName();

            $fileArray = csvToArray($csvFile);
            
            if (!empty($fileArray)) {
                foreach ($fileArray as $key => $value) {
                    if($value['validations'] == 'Success')
                    {
                        $this->withdrawAprrove($value['id'], $value['trxno']);
                    }
                    if($value['validations'] == 'Failed')
                    {
                        $this->withdrawDeny($value['id']);
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
