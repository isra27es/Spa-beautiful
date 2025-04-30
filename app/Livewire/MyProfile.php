<?php

namespace App\Livewire;

use App\Models\Images;
use App\Models\Reservations;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class MyProfile extends Component
{
    #[Title('Mi Perfil')]
    public $reservations = [], $id_reservation;
    public $messageConfirmDelete = false;
    public function openMessage($id){
        $this->id_reservation = $id;
        $this->messageConfirmDelete = true;
    }
    public function closeModal(){
        $this->messageConfirmDelete = false;

    }
    public function mount()
    {
        $reserv = Reservations::select('reservations.*', 'services.*', 'common_attributes.*', 'users.name AS name_user', 'common_attributes.name AS name_service', 'users.users_id AS id_user')
            ->join('users', 'users.users_id', '=', 'reservations.users_id')
            ->join('services', 'services.services_id', '=', 'reservations.services_id')
            ->join('common_attributes', 'common_attributes.common_attributes_id', '=', 'services.common_attributes_id')
            ->where('users.users_id', Auth::user()->users_id)
            ->get();
        foreach ($reserv as $value) {
            $image = Images::where('services_id', $value->services_id)->first();
            $date = $value->reservation_date;
            $carbonDate = Carbon::parse($date);

            $formattedDate = Carbon::parse($date)->translatedFormat('j \d\e F \d\e\l Y');
            $dayOfWeek = $carbonDate->translatedFormat('l');
            $daysRemaining = Carbon::now()->diffInDays($carbonDate, false);

            $horaActual = date('H:i:s');

            if (!$value->status) {
                $status = 'No confrimado';
            } else {
                $status = 'Confirmado';
            }

            if ($daysRemaining > 0) {
                $daysRemaining = round($daysRemaining);
            } elseif($daysRemaining > -1 && $value->reservation_time > $horaActual) {
                $daysRemaining = 1;
            } else{
                $daysRemaining = 0;
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
                'image' => $image->path_images,
            ];
        }
    }
    public function render()
    {
        return view('livewire.my-profile');
    }
    public function cancelService(){
        $reserv = Reservations::find($this->id_reservation);
        $reserv->delete();
        $this->messageConfirmDelete = false;
        $this->mount();
    }
}
