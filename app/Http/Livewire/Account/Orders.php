<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;

class Orders extends Component
{
    public $orders;

    public function render()
    {
        $this->orders = auth()->user()->orders;

        return view('livewire.account.orders')
            ->layout('layouts.account');
    }
}
