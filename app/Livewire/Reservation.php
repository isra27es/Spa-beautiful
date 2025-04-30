<?php

namespace App\Livewire;

use App\Models\Reservations;
use App\Models\Services;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Reservation extends Component
{
    // VERIFICACIONES
    #[Rule('required|date|after:today')]
    public $date;

    public $time;

    // FIN DE LAS VERIFICACIONES
    public $reservedDates = null;
    public $id_service = 0;
    public $service_info = [], $times = [], $reservation = [];
    public $example;
    public $reservation_confirm = false;
    #[Title('Reservacion')]
    public function messages()
    {
        return [
            'date.required' => 'El campo fecha es obligatorio.',
            'date.date' => 'El campo fecha debe ser una fecha válida.',
            'date.after' => 'El campo fecha debe ser posterior a la fecha actual.',
        ];
    }
    public function mount($id)
    {
        $this->time = null;
        $this->id_service = $id;
        $service = Services::select('common_attributes.*', 'services.*')
            ->join('common_attributes', 'common_attributes.common_attributes_id', '=', 'services.common_attributes_id')
            ->where('services_id', $id)
            ->first();

        $this->service_info = [
            'name' => $service->name,
            'price' => $service->price,
            'duration' => $service->duration,
        ];
    }
    public function hours()
    {
        $this->reset('times', 'reservation', 'time');

        $startTime = strtotime('10:00');
        $endTime = strtotime('19:00');
        $reservation = Reservations::select('services.*', 'reservations.*')
            ->join('services', 'services.services_id', '=', 'reservations.services_id')
            ->where('reservations.status', true)
            ->get();

        if ($reservation) {
            foreach ($reservation as $value) {
                $this->reservation[] = [
                    'time' => $value->reservation_time,
                    'date' => $value->reservation_date,
                    'duration' => $value->duration,
                ];
            }
        }

        for ($time = $startTime; $time <= $endTime; $time = strtotime('+30 minutes', $time)) {
            $isReserved = false;
            foreach ($this->reservation as $value) {
                $timeReserved = Carbon::createFromFormat('H:i:s', $value['time'])->format('H:i');
                if ($value['date'] == $this->date && date('H:i', $time) == $timeReserved) {
                    $isReserved = true;
                    $inicio = -30;
                    while ($inicio <= $value['duration']) {
                        $this->times[] = [
                            'hour' => date('H:i', $time),
                            'state' => 'disabled',
                        ];
                        $time = strtotime('+30 minutes', $time);
                        $inicio += 30;
                    }
                    $time = strtotime('-30 minutes', $time);
                    break; // Sal del bucle una vez que la reserva haya sido manejada
                }
            }

            if (!$isReserved) {
                $this->times[] = [
                    'hour' => date('H:i', $time),
                    'state' => '',
                ];
            }
        }
    }

    public function reservate()
    {
        $this->validate();

        $reserv = new Reservations;
        $reserv->reservation_date = $this->date;
        $reserv->reservation_time = $this->time;
        $reserv->status = false;
        $reserv->users_id = Auth::user()->users_id;
        $reserv->services_id = $this->id_service;
        $reserv->save();

        $this->reservation_confirm = true;
    }

    public function render()
    {
        return view('livewire.reservation');
    }
}
