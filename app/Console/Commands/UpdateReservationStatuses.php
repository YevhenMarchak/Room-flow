<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;


#[Signature('reservations:update-statuses')]
#[Description('Update reservation statuses to finished')]
class UpdateReservationStatuses extends Command
{   
    /**
     * Execute the console command.
     */
     public function handle()
    {
        $now = Carbon::now();

        $reservations = Reservation::where('status', 'active')->get();

        foreach ($reservations as $reservation) {

            $reservationEnd = Carbon::parse(
                $reservation->date . ' ' . $reservation->end_time
            );

            if ($now->greaterThan($reservationEnd)) {

                $reservation->status = 'finished';
                $reservation->save();
            }
        }

        $this->info('Reservation statuses updated.');
    }
}
