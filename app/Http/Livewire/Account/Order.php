<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;

class Order extends Component
{
    public $order;

    public function mount(\App\Models\Order $order)
    {
        if ($order->user != auth()->user()) {
            abort(404);
        }
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.account.order')->layout('layouts.account');;
    }
}
