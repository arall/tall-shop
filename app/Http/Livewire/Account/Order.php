<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;

class Order extends Component
{
    public $order;

    public function mount(\App\Models\Order $order)
    {
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.account.order')->layout('layouts.account');;
    }
}
