<?php

namespace App\Livewire\Panel;

use App\Models\Reservations;
use App\Models\Services;
use App\Models\Unregistered_user;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Carbon\Carbon;

class ReservationCreate extends Component
{
    #[Rule('required|min:4|regex:/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/')]
    public $name;

    #[Rule('required|min:8|regex:/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/')]
    public $last_name;
    #[Rule('required|email')]
    public $email;

    #[Rule('required|regex:/^[0-9]{10}$/')]
    public $number_phone;

    #[Rule('required')]
    public $id_service;

    #[Rule('required')]
    public $time;

    #[Rule('required')]
    public $date;

    public $times = [], $reservation = [], $servicios, $reserv_create = 'null';

    public function mount() {}
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El campo nombre debe tener al menos 4 caracteres.',
            'name.regex' => 'El campo nombre solo puede contener letras.',

            'last_name.required' => 'El campo apellido es obligatorio.',
            'last_name.min' => 'El campo apellido debe tener al menos 8 caracteres.',
            'last_name.regex' => 'El campo apellido solo puede contener letras.',

            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El campo correo electrónico debe ser una dirección de correo válida.',

            'number_phone.required' => 'El campo número de teléfono es obligatorio.',
            'number_phone.regex' => 'El campo número de teléfono debe tener 10 dígitos.',

            'id_service.required' => 'El campo de Servicio es obligatorio',

            'time.required' => 'El campo tiempo es obligatorio',

            'date.required' => 'El campo de fecha es obligatorio',
        ];
    }
    public function reservate()
    {
        $this->validate();

        $user = new Unregistered_user;
        $user->name = $this->name;
        $user->lastname = $this->last_name;
        $user->email = $this->email;
        $user->number_phone = $this->number_phone;
        $user->save();

        $reserv = new Reservations;
        $reserv->reservation_date = $this->date;
        $reserv->reservation_time = $this->time;
        $reserv->status = false;
        $reserv->unregistered_users_id  = $user->unregistered_users_id;
        $reserv->services_id = $this->id_service;
        $reserv->save();

        $this->reserv_create = 'exito';
        $this->reset('name','last_name','email','number_phone','time','date','id_service');
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



    public function render()
    {
        $this->find_service();
        return view('livewire.panel.reservation-create');
    }

    public function find_service()
    {
        $servicio = Services::select('services.*', 'service_sections.*', 'common_attributes.*', 'common_attributes.name AS nameC', 'service_sections.name AS nameS')
            ->join('service_sections', 'service_sections.service_sections_id', '=', 'services.service_sections_id')
            ->join('common_attributes', 'common_attributes.common_attributes_id', '=', 'services.common_attributes_id')
            ->get();
        $this->servicios = $servicio;
    }
}
