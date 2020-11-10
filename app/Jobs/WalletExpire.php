<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Auth;
use DB;
use App\UserWalletHistory;

class WalletExpire implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $wallet;
    public $history;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($wallet,$history)
    {
        $this->wallet = $wallet;
        $this->history = $history;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //Get User Wallet

        $todayDate = date('Y-m-d');



        if($this->history){
            
            if($this->wallet->status == 1){

                foreach ($this->history as $key => $history) {
                   // echo($history)."<br>";
                        if($history->expired == 0 && $history->type == 'Credit'){
                           
                            if($todayDate == date('Y-m-d',strtotime($history->expire_at))){
                                
                                $newbalance = $this->wallet->balance - $history->amount;

                                DB::table('user_wallets')->where('user_id',$this->wallet->user->id)->update(['balance' => $newbalance]);

                                DB::table('user_wallet_histories')->where('id','=',$history->id)->update(['expired' => 1]);

                                $walletlog = new UserWalletHistory;

                                $walletlog->wallet_id = $this->wallet->id;
                                $walletlog->type = 'Debit';
                                $walletlog->log = 'Points expired';
                                $walletlog->amount = $history->amount;
                                $walletlog->txn_id = 'WALLET_POINT_EXPIRED_'.uniqid();
                                $walletlog->expire_at = NULL;

                                $walletlog->save();

                            }

                        }

                }

            }

        }
    }
}
