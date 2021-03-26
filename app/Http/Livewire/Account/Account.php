<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class Account extends Component
{
    public $name;
    public $current_password;
    public $email;
    public $new_password;
    public $new_password_confirmation;

    public function mount()
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function render()
    {
        return view('livewire.account.show')->layout('layouts.account');
    }

    public function saveProfile()
    {
        $user = auth()->user();

        $this->validate([
            'name' => [
                'required',
                'string'
            ]
        ]);

        $user->update(['name' => $this->name]);

        $this->emit('saved.profile');
    }

    public function saveLogin()
    {
        $user = auth()->user();

        $rules = [];

        if ($this->email !== $user->email || $this->new_password) {
            $rules[] = ['current_password' => ['required', 'password']];
        }

        if ($this->email !== $user->email) {
            $rules[] = ['email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore(auth()->user()->id),
            ]];
        }

        if ($this->new_password) {
            $rules[] = ['new_password' => ['min:8', 'same:new_password_confirmation']];
        }

        if (!empty($rules)) {
            $this->validate($rules);
        }

        $user->email = $this->email;
        //@todo send email confirmation email
        if ($this->new_password) {
            $user->password = Hash::make($this->new_password);
        }
        $user->save();

        $this->emit('saved.login');
    }
}
