<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use App\Models\User;
use App\Models\Profile;

class Account extends Component
{
    public User $user;
    public Profile $profile;

    public function render()
    {
        return view('livewire.account.show');
    }
}
