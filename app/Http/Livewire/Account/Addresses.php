<?php

namespace App\Http\Livewire\Account;

use Livewire\Component;
use App\Models\UserAddress;
use PragmaRX\Countries\Package\Countries;

class Addresses extends Component
{
    public $addresses;
    public UserAddress $address;
    public $showModal = false;

    protected $rules = [
        'address.firstname' => 'required|string',
        'address.lastname' => 'required|string',
        'address.country' => 'required|string',
        'address.region' => 'required|string',
        'address.city' => 'required|string',
        'address.address' => 'required|string',
        'address.zip' => 'required',
        'address.phone' => 'required',
    ];

    public function render()
    {
        $this->addresses = auth()->user()->addresses;

        return view('livewire.account.addresses')
            ->with('countries', Countries::all()->pluck('name.common', 'cca2')->toArray())
            ->layout('layouts.account');
    }

    public function create()
    {
        $this->showModal = true;
        $this->address = new UserAddress;
    }

    public function edit($id)
    {
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

    public function getById($id)
    {
        return auth()->user()->addresses()->where('id', $id)->firstOrFail();
    }
}
