<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;


class Register extends Component
{
    #[Rule('required|min:4|regex:/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/')]
    public $name;

    #[Rule('required|min:8|regex:/^[a-zA-Z\sáéíóúÁÉÍÓÚñÑ]+$/')]
    public $last_name;

    #[Rule('required|numeric|digits_between:10,15')]
    public $number_phone;

    #[Rule('required|date_format:Y|digits:4|before_or_equal:now')]
    public $year;

    #[Rule('required|email|unique:users,email')]
    public $email;

    #[Rule('nullable|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d!@#$%^&*()\-_=+{};:,<.>]{8,}$/')]
    public $password;

    #[Rule('required|same:password')]
    public $password_confirm;
    public $showPassword = "password", $showPasswordConfirm = "password";
    #[Title('Registro')]
    public function showPass()
    {
        if ($this->showPassword == "password") {
            $this->showPassword = "text";
        } else {
            $this->showPassword = "password";
        }
    }
    public function showPassConfirm()
    {
        if ($this->showPasswordConfirm == "password") {
            $this->showPasswordConfirm = "text";
        } else {
            $this->showPasswordConfirm = "password";
        }
    }
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El campo nombre debe tener al menos 4 caracteres.',
            'name.regex' => 'El campo nombre solo puede contener letras.',

            'last_name.required' => 'El campo apellido es obligatorio.',
            'last_name.min' => 'El campo apellido debe tener al menos 8 caracteres.',
            'last_name.regex' => 'El campo apellido solo puede contener letras.',

            'number_phone.required' => 'El campo número de teléfono es obligatorio.',
            'number_phone.numeric' => 'El campo número de teléfono debe ser numérico.',
            'number_phone.digits_between' => 'El campo número de teléfono debe tener entre 10 y 15 dígitos.',

            'year.required' => 'El campo año es obligatorio.',
            // 'year.date_format' => 'El campo año debe tener el formato Y (ejemplo: 2022).',

            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El campo correo electrónico debe ser una dirección de correo válida.',
            'email.unique' => 'El correo electrónico ya está registrado.',

            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'El campo contraseña debe contener al menos una letra mayúscula, una letra minúscula y un número.',

            'password_confirm.required' => 'El campo confirmar contraseña es obligatorio.',
            'password_confirm.same' => 'El campo confirmar contraseña debe ser igual al campo contraseña.',
        ];
    }
    public function render()
    {
        return view('livewire.register');
    }
    public function Register()
    {
        $this->validate();
        $user = new User;
        $user->name = $this->name;
        $user->lastname = $this->last_name;
        $user->number_phone = $this->number_phone;
        $user->year_birth = $this->year;
        $user->email = $this->email;
        $user->roles_id = 2;
        $user->password = Hash::make($this->password);
        $user->save();

        Auth::login($user);
        return redirect()->route('home');
    }
}
