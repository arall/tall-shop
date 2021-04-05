<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use App\Models\UserInvoiceAddress;
use PragmaRX\Countries\Package\Countries;

class InvoiceAddresses extends Component
{
    public $addresses;
    public UserInvoiceAddress $address;
    public $showModal = false;

    protected $rules = [
        'address.vat' => 'required|string',
        'address.name' => 'required|string',
        'address.country' => 'required|string',
        'address.region' => 'required|string',
        'address.city' => 'required|string',
        'address.address' => 'required|string',
        'address.zip' => 'required',
        'address.phone' => 'required',
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
        $this->address = new UserInvoiceAddress;
    }

    public function edit($id)
    {
        $this->showModal = true;
        $this->address = $this->getById($id);
    }

    public function save()
    {
        $this->validate();

        if ($this->address->id) {
            // Check ownership
            $this->getById($this->address->id);

            $this->address->save();
        } else {
            auth()->user()->addresses()->save($this->address);
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
