<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Client;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        Carbon::setWeekStartsAt(Carbon::SATURDAY);
        Carbon::setWeekEndsAt(Carbon::FRIDAY);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $data['five_year_ago'] = Carbon::now()->subYears(5)->format('Y');
        $today = Carbon::now()->toDateString();
        $yesterday = Carbon::now()->subDays(1)->toDateString();
        $previous_month = Carbon::now()->subMonth(1)->format('m');
        $this_week_start = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this_week_end = Carbon::now()->endOfWeek()->format('Y-m-d');
        $pre_week_start = date('Y-m-d', strtotime('-7 days', strtotime($this_week_start)));
        $pre_week_end = date('Y-m-d', strtotime('-7 days', strtotime($this_week_end)));

        $data['total_expense'] = DB::table('accounts')->where(['trx_for' => 'mistrimama', 'status' => 'debit'])->sum('amount');
        $data['total_reacharge'] = DB::table('accounts')->where(['trx_for' => 'service_provider', 'ref' => 'recharge'])->sum('amount');

        $data['total_income'] = DB::table('accounts')->where(['trx_for' => 'mistrimama', 'status' => 'credit'])->sum('amount');
        $data['today_income'] = DB::table('accounts')->where(['trx_for' => 'mistrimama', 'status' => 'credit', 'date' => $today])->sum('amount');
        $data['yesterday_income'] = DB::table('accounts')->where(['trx_for' => 'mistrimama', 'status' => 'credit', 'date' => $yesterday])->sum('amount');
        $data['this_month_income'] = DB::table('accounts')->where(['trx_for' => 'mistrimama', 'status' => 'credit'])->whereMonth('date', $month)->whereYear('date', $year)->sum('amount');
        $data['previous_month_income'] = DB::table('accounts')->where(['trx_for' => 'mistrimama', 'status' => 'credit'])->whereMonth('date', $previous_month)->whereYear('date', $year)->sum('amount');

        $data['service_provider_withdraw_week'] = DB::table('withdraw_requests')->where(['type' => 'withdraw', 'status' => 1])->whereBetween('created_at', [$this_week_start, $this_week_end])->sum('amount');
        $data['service_provider_withdraw_previous_week'] = DB::table('withdraw_requests')->where(['type' => 'withdraw', 'status' => 1])->whereBetween('created_at', [$pre_week_start, $pre_week_end])->sum('amount');
        $data['service_provider_withdraw_this_month'] = DB::table('withdraw_requests')->where(['type' => 'withdraw', 'status' => 1])->whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('amount');
        $data['service_provider_withdraw_previous_month'] = DB::table('withdraw_requests')->where(['type' => 'withdraw', 'status' => 1])->whereMonth('created_at', $previous_month)->whereYear('created_at', $year)->sum('amount');
        
        $data['client_cashout_week'] = DB::table('withdraw_requests')->where(['type' => 'cash_out', 'status' => 1])->whereBetween('created_at', [$this_week_start, $this_week_end])->sum('amount');
        $data['client_cashout_previous_week'] = DB::table('withdraw_requests')->where(['type' => 'cash_out', 'status' => 1])->whereBetween('created_at', [$pre_week_start, $pre_week_end])->sum('amount');
        $data['client_cashout_this_month'] = DB::table('withdraw_requests')->where(['type' => 'cash_out', 'status' => 1])->whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('amount');
        $data['client_cashout_previous_month'] = DB::table('withdraw_requests')->where(['type' => 'cash_out', 'status' => 1])->whereMonth('created_at', $previous_month)->whereYear('created_at', $year)->sum('amount');

        $data['service_provider_recharge_week'] = DB::table('recharge_requests')->where(['status' => 1])->whereBetween('created_at', [$this_week_start, $this_week_end])->sum('amount');
        $data['service_provider_recharge_previous_week'] = DB::table('recharge_requests')->where(['status' => 1])->whereBetween('created_at', [$pre_week_start, $pre_week_end])->sum('amount');
        $data['service_provider_recharge_this_month'] = DB::table('recharge_requests')->where(['status' => 1])->whereMonth('created_at', $month)->whereYear('created_at', $year)->sum('amount');
        $data['service_provider_recharge_previous_month'] = DB::table('recharge_requests')->where(['status' => 1])->whereMonth('created_at', $previous_month)->whereYear('created_at', $year)->sum('amount');

        $data['total_order'] = DB::table('orders')->where('status', '5')->whereMonth('date', $month)->whereYear('date', $year)->count();

        $data['esp'] = DB::table('service_providers')->where('type', 'esp')->count();
        $data['fsp'] = DB::table('service_providers')->where('type', 'fsp')->count();
        $data['comrades'] = DB::table('comrades')->count();
        $data['clients'] = DB::table('clients')->count();

        $data['esp_active'] = DB::table('service_providers')->where('status', 1)->where('type', 'esp')->count();
        $data['fsp_active'] = DB::table('service_providers')->where('status', 1)->where('type', 'fsp')->count();
        $data['comrades_active'] = DB::table('comrades')->where('status', 1)->count();
        $data['clients_active'] = DB::table('users')->join('clients', 'clients.user_id','=','users.id')->where('status', 1)->count();
        
        $data['esp_inactive'] = DB::table('service_providers')->where('status', 0)->where('type', 'esp')->count();
        $data['fsp_inactive'] = DB::table('service_providers')->where('status', 0)->where('type', 'fsp')->count();
        $data['comrades_inactive'] = DB::table('comrades')->where('status', 0)->count();
        $data['clients_inactive'] = DB::table('users')->join('clients', 'clients.user_id','=','users.id')->where('status', 0)->count();

        $data['clients_registrations_year_wise_reports'] = Client::select(
            DB::raw('count(id) as total_clients'),
            DB::raw('YEAR(created_at) year'),
        )->groupby('year')->orderBy('year')->limit(5)->pluck('total_clients', 'year')->toArray();
        
        
        $data['clients_registrations_reports'] = Client::select(
            DB::raw('count(id) as total_clients'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month'),
            DB::raw("DATE_FORMAT(created_at, '%M') month_name"),
        )->whereYear('created_at', $year)->groupby('year','month', 'month_name')->orderBy('month')->get();
        $data['current_year'] = $year;
        return view('dashboard', $data);
    }
}
