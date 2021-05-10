<?php

namespace App\Console\Commands;

use App\Models\Chat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TerminateChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terminate:chats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $date=date('Y-m-d', strtotime('-1 days'));
        $chats=Chat::with('shoppr')->where('is_terminated', false)
            ->where(DB::raw('DATE(created_at)'), '<=', $date)
            ->get();
        foreach($chats as $c){
            $c->update(['is_terminated'=>true]);
            if($c->shoppr){
                $c->shoppr->is_available=true;
                $c->shoppr->save();
            }
        }
    }
}
