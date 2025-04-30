<?php

namespace App\Livewire\Panel;

use App\Models\User;
use Livewire\Component;

class Clientes extends Component
{
    public $customers = [];
    public $search = '';
    public function mount()
    {
        $currentYear = date('Y');

        $this->reset('customers');
        $customer = User::where('roles_id', 2)->get();
        foreach ($customer as $cust) {
            $age = $currentYear - $cust->year_birth;
            $this->customers[] = [
                'name' => $cust->name,
                'lastname' => $cust->lastname,
                'number_phone' => $cust->number_phone,
                'year_birth' => $cust->year_birth,
                'email' => $cust->email,
                'age' => $age,
            ];
        }
    }
    public function render()
    {
        if ($this->search) {
            $this->reset('customers');
            $search = $this->search;
            $users = User::where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('lastname', 'like', '%' . $search . '%')
                    ->orWhere('number_phone', 'like', '%' . $search . '%')
                    ->orWhere('year_birth', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })->get();
            $currentYear = date('Y');
            foreach ($users as $cust) {
                $age = $currentYear - $cust->year_birth;
                $this->customers[] = [
                    'name' => $cust->name,
                    'lastname' => $cust->lastname,
                    'number_phone' => $cust->number_phone,
                    'year_birth' => $cust->year_birth,
                    'email' => $cust->email,
                    'age' => $age,
                ];
            }

            return view('livewire.panel.clientes', [
                'users' => $this->customers,
            ]);
        } else {

            $this->reset('customers');
            $users = User::all();
            $currentYear = date('Y');
            foreach ($users as $cust) {
                $age = $currentYear - $cust->year_birth;
                $this->customers[] = [
                    'name' => $cust->name,
                    'lastname' => $cust->lastname,
                    'number_phone' => $cust->number_phone,
                    'year_birth' => $cust->year_birth,
                    'email' => $cust->email,
                    'age' => $age,
                ];
            }
            return view('livewire.panel.clientes', [
                'users' => $this->customers,
            ]);
        }
    }
}
