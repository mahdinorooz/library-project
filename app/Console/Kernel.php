<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\Penalty;
use App\Models\BookUser;
use App\Traits\SmsTrait;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    use SmsTrait;
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $bookUsers = BookUser::where('status', 'is_borrowed')->get();
            foreach ($bookUsers as $bookUser) {
                $returnDate = $bookUser->book()->pluck('return_date');
                $bookPrice = $bookUser->book()->pluck('price');
                $userId = $bookUser->user()->pluck('id');
                // dd($bookPrice[0]);
                if ($returnDate[0] <= Carbon::now()) {
                    $penalty = $bookPrice[0] / 20;
                    Penalty::create([
                        'user_id' => $userId[0],
                        'amount' => $penalty,
                        'transaction_id' => rand(111111111, 999999999)
                    ]);
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
