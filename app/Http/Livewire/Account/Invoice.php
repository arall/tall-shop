<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;

class Invoice extends Component
{
    public $invoice;

    public function mount(\App\Models\Invoice $invoice)
    {
        if ($invoice->user != auth()->user() || !$invoice->isSubmitted()) {
            abort(404);
        }
        $this->invoice = $invoice;
    }

    public function render()
    {
        return view('livewire.account.invoice')->layout('layouts.account');;
    }
}
