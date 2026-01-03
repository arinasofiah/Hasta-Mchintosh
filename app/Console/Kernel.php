<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Bookings;
use App\Models\Vehicles;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Run every minute
        $schedule->call(function () {
            $now = Carbon::now();

            // Find all pending bookings past expiration
            $expiredBookings = Bookings::where('bookingStatus', 'pending')
                ->where('reservation_expires_at', '<', $now)
                ->get();

            foreach ($expiredBookings as $booking) {
                // Set booking as expired
                $booking->bookingStatus = 'expired';
                $booking->save();

                // Set vehicle back to available
                $vehicle = Vehicles::find($booking->vehicleID);
                if ($vehicle) {
                    $vehicle->status = 'available';
                    $vehicle->save();
                }
            }
        })->everyMinute();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
