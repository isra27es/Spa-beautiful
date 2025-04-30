<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    #[Rule('required|email|exists:users,email')]
    public $email;
    #[Rule('required|min:8')]
    public $password;
    public $remember;
    public $showPassword = "password";
    #[Title('Login')]
    public function messages(){
        return [
            'email.required' => 'El campo de Correo es Obligatorio',
            'email.email' => 'El campo de correo es inválido',
            'email.exists' => 'El correo no existe ',
            'password.required' => 'El campo de Contraseña es Obligatorio',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres'
        ];
    }
    #[Title('Login')]
    public function render()
    {
        return view('livewire.login');
    }
    public function showPass()
    {
        if ($this->showPassword == "password") {
            $this->showPassword = "text";
        } else {
            $this->showPassword = "password";
        }
    }

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();
            return redirect()->route('home');
        } else {
            $this->addError('email', 'Las credenciales no coinciden con nuestros registros.');
        }

    }
}
