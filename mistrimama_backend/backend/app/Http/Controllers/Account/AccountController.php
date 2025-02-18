<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Account;
use App\AccountsHeading;
use App\RewardPoint;
use App\User;
use App\ServiceProvider;
use App\Scheme;
use Carbon\Carbon;
use App\SMS;
use DataTables;

class AccountController extends Controller
{

    public function index()
    {
        return view('account.cashbook.transactions');
    }

    public function accountFilter(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $accounts = Account::where(['trx_for' => 'mistrimama', 'user_id' => auth()->user()->id]);
        if($from_date != '' && $to_date != '')
        {
            $accounts->whereBetween('date', [$from_date, $to_date]);
        }
        $accounts->orderBy('id', 'desc');

        return datatables()->eloquent($accounts)->toJson();
        
    }

    public function create()
    {
        return view('account.cashbook.create');
    }

    public function servicePrividerTransactions($id)
    {
        $data['details'] = ServiceProvider::where(['user_id' => $id])->first();
        $data['schemes'] = Scheme::where(['service_provider_id' => $id])->orderBy('id', 'desc')->first();
        $data['accounts'] = Account::where(['trx_for' => 'service_provider', 'user_id' => $id])->orderBy('id', 'desc')->get();
        return view('sp.transactions', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading_type'    => 'required|in:investment,expenses,revenue',
            'transaction_heading' => 'required|exists:accounts_headings,id',
            'date' => 'required|date_format:Y-m-d',
            'payment_mode' => 'required',
            'amount' => 'required|numeric',
        ]);

        try{
            DB::beginTransaction();

            $transaction_heading = AccountsHeading::where('id', $request->transaction_heading)->first();

            $account = new Account();
            $account->trx_for = 'mistrimama';
            $account->user_id = auth()->user()->id;
            $account->amount = $request->amount;
            $account->trxno = TrxId();
            $account->type = $request->payment_mode;
            $account->heading_type = $transaction_heading->heading_type;
            $account->heading = $transaction_heading->title;
            $account->details = $request->details;
            $account->ref = 'admin';
            $account->ref_key = auth()->user()->id;
            $account->status = $transaction_heading->type;
            $account->date = $request->date;
            $account->save();

            DB::commit();

            toastr()->success('Transaction create successfully!');
            return redirect()->back();

        }catch(\Exception $e){
            DB::rollback();
        }
        toastr()->error('Transaction create failed');
    }


    /** this methods are applicabel for every user */
    public static function balance($user_id)
    {
        $balance = self::totalCreditLifetime($user_id) - self::totalDebitLifetime($user_id);
        return $balance;
    }

    public static function totalIncomeLifetime($user_id)
    {
        return $creditAmount = Account::where(['user_id' => $user_id, 'status' => 'income'])->sum('amount');
    }

    public static function totalCreditLifetime($user_id)
    {
        return $creditAmount = Account::where(['user_id' => $user_id, 'status' => 'credit'])->sum('amount');
    }

    public static function totalDebitLifetime($user_id)
    {
        return $creditAmount = Account::where(['user_id' => $user_id, 'status' => 'debit'])->sum('amount');
    }

    public static function WithdrawRequestDebit($data)
    {
        try{
            DB::beginTransaction();
            
            $account = new Account();
            $account->trx_for = $data['trx_for'];
            $account->user_id = $data['user_id'];
            $account->amount = $data['amount'];
            $account->trxno = TrxId();
            $account->type = 'Cash';
            $account->heading_type = 'expense';
            $account->heading = $data['heading'];
            $account->details = $data['details'];
            $account->ref = $data['ref'];
            $account->ref_key = $data['ref_key'];
            $account->status = 'debit';
            $account->date = Carbon::now()->toDateString();
            $account->save();

            DB::commit();
            
            return true;

        }catch(\Exception $e){
            DB::rollback();
        }
        return false;
    }

    public static function WithdrawRequestCredit($data)
    {
        try{
            DB::beginTransaction();
            
            $account = new Account();
            $account->trx_for = $data['trx_for'];
            $account->user_id = $data['user_id'];
            $account->amount = $data['amount'];
            $account->trxno = TrxId();
            $account->type = 'Virtual';
            $account->heading_type = 'revenue';
            $account->heading = $data['heading'];
            $account->details = $data['details'];
            $account->ref = $data['ref'];
            $account->ref_key = $data['ref_key'];
            $account->status = 'credit';
            $account->date = Carbon::now()->toDateString();
            $account->save();

            DB::commit();
            
            return true;

        }catch(\Exception $e){
            DB::rollback();
        }
        return false;
    }

    public static function serviceProviderIncome($data)
    {
        try{
            DB::beginTransaction();
            
            $account = new Account();
            $account->trx_for = 'service_provider';
            $account->user_id = $data['user_id'];
            $account->amount = $data['amount'];
            $account->trxno = TrxId();
            $account->type = $data['type'];
            $account->heading_type = 'revenue';
            $account->heading = $data['heading'];
            $account->details = $data['details'];
            $account->ref = 'order';
            $account->ref_key = $data['ref_key'];
            $account->status = $data['status'];
            $account->date = Carbon::now()->toDateString();
            $account->save();

            DB::commit();
            
            return true;

        }catch(\Exception $e){
            DB::rollback();
        }
        return false;
    }

    public static function rewardPointAdd($order)
    {
        $user = User::where('ref_code', $order->ref_code)->first();
        $earned_point = round((($order->total_price * 30) / 100));
        $amount = round(($earned_point/3), 2);

        try{
            DB::beginTransaction();

            $rp = RewardPoint::create([
                'user_id' => $user->id,
                'rp' => $earned_point,
                'status' => 'add',
                'details' => "".$user->name." (".$user->phone.") got referal point. Order No#".$order->order_no." (Order bill BDT ".$order->total_price.")",
            ]);
            $rp->amount = $amount;
            self::rewardCredit($rp, $user->id);

            $total_add = RewardPoint::where(['user_id' => $user->id, 'status' => 'add'])->get()->sum('rp');
            $total_remove = RewardPoint::where(['user_id' => $user->id, 'status' => 'remove'])->get()->sum('rp');

            SMS::send($user->phone, "Thank you for referring Mistri Mama. You earned reward point: ".$earned_point.". Your total reward point: ".($total_add - $total_remove).".");

            DB::commit();
            return 1;
        }catch(\Exception $e){
            return $e;
            DB::rollback();
        }
        return 0;
    }

    public static function rewardCredit($redeem_point, $user_id)
    {
        try{
            DB::beginTransaction();

            $account = new Account();
            $account->trx_for = 'client';
            $account->user_id = $user_id;
            $account->amount = $redeem_point->amount;
            $account->trxno = TrxId();
            $account->type = 'Virtual';
            $account->heading_type = 'revenue';
            $account->heading = 'Reward Point';
            $account->details = $redeem_point->details;
            $account->ref = 'reward_points';
            $account->ref_key = $redeem_point->id;
            $account->status = 'credit';
            $account->date = Carbon::now()->toDateString();
            $account->save();

            DB::commit();

            return true;
        }catch(\Exception $e){
            DB::rollback();
        }
        return false;
    }
    
    public function outstandingBalanceAdd($user_id, $order)
    {
        // $acc = new Account();
        // $acc->heading = 'Order Charge';
        // $acc->amount = $order->total_price;
        // $acc->user_id = $user_id;
        // $acc->trxno = TrxId();
        // $acc->type = 'virtual';
        // $acc->details = 'Order#' . $order->order_no . 'charge';
        // $acc->ref = 'order';
        // $acc->ref_key = $order->id;
        // $acc->status = 'debit';
        // $acc->date = Carbon::now()->toDateString();
        // if ($acc->save()) {
        //     return 1;
        // } else {
        //     return 0;
        // }
        return $order;
    }
}
