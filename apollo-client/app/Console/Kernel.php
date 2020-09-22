<?php
namespace App\Console;


use CjsConsole\Scheduling\Schedule;
use CjsConsole\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Apollo\Console\Commands\Apollo\Test',              // test demo
        'App\Apollo\Console\Commands\Apollo\ApolloSync',        // 同步某个配置
        'App\Apollo\Console\Commands\Apollo\ApolloSyncAll',     // 同步所有配置
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //设置跑的时间表
//        $schedule->command('apollo:test')->cron('* * * * *');
        $schedule->command('apollo:apollo_sync_all')->cron('* * * * *');
    }
}
