<?php

namespace App\Livewire\Panel;

use App\Models\Reservations as ModelsReservations;
use Livewire\Component;
use Carbon\Carbon;

class Reservations extends Component
{
    public $reservations = [];
    public function mount()
    {
        $this->reset('reservations');
        $reserv = ModelsReservations::select('reservations.*', 'services.*', 'common_attributes.*', 'users.name AS name_user', 'common_attributes.name AS name_service')
            ->join('users', 'users.users_id', '=', 'reservations.users_id')
            ->join('services', 'services.services_id', '=', 'reservations.services_id')
            ->join('common_attributes', 'common_attributes.common_attributes_id', '=', 'services.common_attributes_id')
            ->get();

        $reserv2 = ModelsReservations::select('reservations.*', 'services.*', 'common_attributes.*', 'unregistered_users.name AS name_user', 'common_attributes.name AS name_service')
        ->join('unregistered_users', 'unregistered_users.unregistered_users_id', '=', 'reservations.unregistered_users_id')
        ->join('services', 'services.services_id', '=', 'reservations.services_id')
        ->join('common_attributes', 'common_attributes.common_attributes_id', '=', 'services.common_attributes_id')
        ->get();

        foreach ($reserv2 as $value) {
            $date = $value->reservation_date;
            $carbonDate = Carbon::parse($date);

            $formattedDate = Carbon::parse($date)->translatedFormat('j \d\e F \d\e\l Y');
            $dayOfWeek = $carbonDate->translatedFormat('l');
            $daysRemaining = Carbon::now()->diffInDays($carbonDate, false);



            if (!$value->status) {
                $status = 'No confrimado';
            } else {
                $status = 'Confirmado';
            }



            if ($daysRemaining > 0) {
                $daysRemaining = round($daysRemaining) . ' dias';
            }  elseif($daysRemaining > -1) {
                $daysRemaining = '0 dias, es el dia de hoy';
            } else{
                $daysRemaining = '0 Dias ya paso';
            }


            $this->reservations[] = [
                'id' => $value->reservations_id,
                'name_user' => $value->name_user,
                'name_service' => $value->name_service,
                'price' => $value->price,
                'duration' => $value->duration,
                'date' => $formattedDate,
                'time' => $value->reservation_time,
                'status' => $value->status,
                'status_messaage' => $status,
                'day' => $dayOfWeek,
                'days_remaining' => $daysRemaining,
            ];
        }

        foreach ($reserv as $value) {
            $date = $value->reservation_date;
            $carbonDate = Carbon::parse($date);

            $formattedDate = Carbon::parse($date)->translatedFormat('j \d\e F \d\e\l Y');
            $dayOfWeek = $carbonDate->translatedFormat('l');
            $daysRemaining = Carbon::now()->diffInDays($carbonDate, false);



            if (!$value->status) {
                $status = 'No confrimado';
            } else {
                $status = 'Confirmado';
            }



            if ($daysRemaining > 0) {
                $daysRemaining = round($daysRemaining) . ' dias';
            }  elseif($daysRemaining > -1) {
                $daysRemaining = '0 dias, es el dia de hoy';
            } else{
                $daysRemaining = '0 Dias ya paso';
            }


            $this->reservations[] = [
                'id' => $value->reservations_id,
                'name_user' => $value->name_user,
                'name_service' => $value->name_service,
                'price' => $value->price,
                'duration' => $value->duration,
                'date' => $formattedDate,
                'time' => $value->reservation_time,
                'status' => $value->status,
                'status_messaage' => $status,
                'day' => $dayOfWeek,
                'days_remaining' => $daysRemaining,
            ];
        }
    }
    public function accept_reservation($id)
    {
        $reserv = ModelsReservations::find($id);
        $reserv->status = true;
        $reserv->save();

        $this->mount();
    }

    public function cancel_reservation($id)
    {
        $reserv = ModelsReservations::find($id);
        $reserv->status = false;
        $reserv->save();

        $this->mount();
    }

    public function delete_reservation($id)
    {
        $reserv = ModelsReservations::find($id);
        $reserv->delete();

        $this->mount();
    }

    public function render()
    {
        return view('livewire.panel.reservations');
    }
}
