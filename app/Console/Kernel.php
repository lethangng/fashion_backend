<?php

namespace App\Console;

use App\Models\Product;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Kích hoạt hàm mỗi ngày vào lúc nửa đêm
        $schedule->call(function () {
            Product::generateRecommendations('similar_products');
        })->daily();

        // Cho chạy
        // php artisan schedule:work  

        // $schedule->call(function () {
        //     info('Gọi nó lại trong mỗi 30s phút');
        // })->everyThirtySeconds();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
