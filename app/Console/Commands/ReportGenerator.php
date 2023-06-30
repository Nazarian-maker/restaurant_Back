<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReportGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:report';

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
    public function handle() {
        $date = Carbon::yesterday()->toDateString();
        $orders = Order::where([['closed_at', 'like', "%{$date}%"], ['is_closed', '=', 'true']])->get();
        $orders_amount = $orders->count();
        $daily_income = $orders->sum('total_cost');
        Report::create(['orders_amount' => $orders_amount, 'daily_income' => $daily_income]);
    }
}
