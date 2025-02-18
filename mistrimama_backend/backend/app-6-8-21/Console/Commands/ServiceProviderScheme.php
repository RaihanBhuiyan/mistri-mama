<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Account;
use App\ServiceProvider;
use App\OrderDetail;
use App\User;
use Carbon\Carbon;
use Notification;
use App\Notifications\GeneralNotification;
use App\SMS;

class ServiceProviderScheme extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheme:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check service provider if eligable for scheme and give them there gift.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user = User::whereHas("roles", function ($q) {
            $q->where(['name' => 'admin']);
        })->first();

        $service_providers = ServiceProvider::where(['status' => 1])->get();
        $week_start = Carbon::now('Asia/Dhaka')->subDays(7)->toDateTimeString();
        $today = Carbon::now('Asia/Dhaka')->toDateTimeString();
        $date = Carbon::now('Asia/Dhaka')->toDateString();
        
        $bonus = 0.00;
        $num = 0;
        if(!empty($service_providers) && !empty($user))
        {
            foreach($service_providers as $key => $service_provider)
            {
                $get_last_week_orders = OrderDetail::where(['service_provider_id' => $service_provider->id, 'status' => 5])->whereIn('order_from', ['esp', 'comrade'])->whereBetween('sp_accept_time', [$week_start, $today])->count();
                if($get_last_week_orders >= 15)
                {
                    $num = $num + 1;

                    $mistrimama_account = new Account();
                    $mistrimama_account->user_id = $user->id;
                    $mistrimama_account->trxno = TrxId();
                    $mistrimama_account->type = 'virtual';
                    $mistrimama_account->status = 'debit';
                    $mistrimama_account->date = $date;

                    $service_provider_account = new Account();

                    $service_provider_account->user_id = $service_provider->id;
                    $service_provider_account->trxno = TrxId();
                    $service_provider_account->type = 'virtual';
                    $service_provider_account->status = 'credit';
                    $service_provider_account->date = $date;

                    if($get_last_week_orders >= 15 && $get_last_week_orders < 20)
                    {
                        $bonus = 50.00;
                        $heading = 'Bonus Category(C)';
                        $mistrimama_account_details = 'Mistrimama give BDT ' . $bonus . ' service provider bonus to '.$service_provider->name.' ('.$service_provider->sp_code.')';
                        $service_provider_account_details = 'You got BDT ' . $bonus . ' service provider bonus from mistrimama.';
                    }

                    if($get_last_week_orders >= 20 && $get_last_week_orders < 30)
                    {
                        $bonus = 80.00;
                        $heading = 'Bonus Category(C)';
                        $mistrimama_account_details = 'Mistrimama give BDT ' . $bonus . ' service provider bonus to '.$service_provider->name.' ('.$service_provider->sp_code.')';
                        $service_provider_account_details = 'You got BDT ' . $bonus . ' service provider bonus from mistrimama.';
                    }

                    if($get_last_week_orders >= 30)
                    {
                        $bonus = 150.00;
                        $heading = 'Bonus Category(C)';
                        $mistrimama_account_details = 'Mistrimama give BDT ' . $bonus . ' service provider bonus to '.$service_provider->name.' ('.$service_provider->sp_code.')';
                        $service_provider_account_details = 'You got BDT ' . $bonus . ' service provider bonus from mistrimama.';
                    }

                    $mistrimama_account->heading = $heading;
                    $service_provider_account->heading = $heading;

                    $mistrimama_account->details = $mistrimama_account_details;
                    $service_provider_account->details = $service_provider_account_details;

                    $mistrimama_account->amount = $bonus;
                    $service_provider_account->amount = $bonus;

                    $mistrimama_account->save();
                    $service_provider_account->save();
                    SMS::send($serviceprovider->phone, 'You got BDT ' . $bonus . ' bonus from mistrimama');
                    Notification::send($service_provider->user, new GeneralNotification(['title' => 'You got BDT ' . $bonus . ' bonus from mistrimama']));
                }
            }
        }
        Notification::send($user, new GeneralNotification(['title' => 'Service provider scheme was executed. Got bonus '.$num.' service provider.']));
        SMS::send($user->phone, 'Service provider scheme was executed');
    }
}
