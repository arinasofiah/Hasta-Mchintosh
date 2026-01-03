<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bookings;
use App\Models\Vehicles;
use Carbon\Carbon;

class ExpirePendingBookings extends Command
{
    protected $signature = 'bookings:expire';
    protected $description = 'Cancel pending bookings if not paid within 10 minutes';

    public function handle()
    {
        $now = Carbon::now();

        // Find expired bookings
        $expiredBookings = Bookings::where('bookingStatus', 'pending')
            ->where('reservation_expires_at', '<=', $now)
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->bookingStatus = 'cancelled';
            $booking->save();

            // Free the vehicle
            $vehicle = Vehicles::find($booking->vehicleID);
            if ($vehicle) {
                $vehicle->status = 'available';
                $vehicle->reservation_expires_at = null;
                $vehicle->save();
            }
        }

        $this->info(count($expiredBookings) . ' pending bookings expired.');
    }
}
