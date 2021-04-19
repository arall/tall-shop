<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use App\Models\UserInvoiceAddress;
use PragmaRX\Countries\Package\Countries;

class InvoiceAddresses extends Component
{
    public $addresses;
    public UserInvoiceAddress $invoiceAddress;
    public $showModal = false;

    protected $rules = [
        'invoiceAddress.vat' => 'required|string',
        'invoiceAddress.name' => 'required|string',
        'invoiceAddress.country' => 'required|string',
        'invoiceAddress.region' => 'required|string',
        'invoiceAddress.city' => 'required|string',
        'invoiceAddress.address' => 'required|string',
        'invoiceAddress.zip' => 'required',
        'invoiceAddress.phone' => 'required',
    ];

    public function render()
    {
        $this->addresses = auth()->user()->invoiceAddresses;

        return view('livewire.account.invoice-addresses')
            ->with('countries', Countries::all()->pluck('name.common', 'cca2')->toArray())
            ->layout('layouts.account');
    }

    public function create()
    {
        $this->showModal = true;
        $this->invoiceAddress = new UserInvoiceAddress;
    }

    public function edit($id)
    {
        $this->showModal = true;
        $this->invoiceAddress = $this->getById($id);
    }

    public function save()
    {
        $this->validate();

        if ($this->invoiceAddress->id) {
            // Check ownership
            $this->getById($this->invoiceAddress->id);

            $this->invoiceAddress->save();
        } else {
            auth()->user()->invoiceAddresses()->save($this->invoiceAddress);
        }

        $this->showModal = false;
    }

    public function destroy($id)
    {
        $this->getById($id)->delete();
    }

    public function favorite($id)
    {
        $address = $this->getById($id);
        if ($address->favorite) {
            $address->setAsNonFavorite();
        } else {
            $address->setAsFavorite();
        }
    }

    public function getById($id)
    {
        return auth()->user()->invoiceAddresses()->where('id', $id)->firstOrFail();
    }
}
