<?php

namespace App\Http\Livewire\Shop;

use App\Helpers\Cart as CartHelper;
use App\Helpers\Taxes as TaxesHelper;
use App\Helpers\Location as LocationHelper;
use App\Models\PaymentMethod;
use Livewire\Component;
use PragmaRX\Countries\Package\Countries;
use App\Models\Order;
use App\Models\ShippingCarrier;
use App\Models\UserAddress;
use App\Models\UserInvoiceAddress;

class Checkout extends Component
{
    /**
     * Selected Shipping Address Id.
     *
     * @var int
     */
    public $addressId;

    /**
     * Shipping Address data.
     *
     * @var UserInvoiceAddress
     */
    public UserAddress $address;

    /**
     * Selected Invoice Address Id.
     *
     * @var int
     */
    public $invoiceAddressId = 0;

    /**
     * Invoice Address data.
     *
     * @var UserInvoiceAddress
     */
    public UserInvoiceAddress $invoiceAddress;

    /**
     * Show or hide the invoice address form.
     *
     * @var boolean
     */
    public $showInvoiceForm = false;

    /**
     * Selected Shipping Carrier Id.
     *
     * @var int
     */
    public $shippingCarrierId;

    /**
     * Selected PAyment Method Id.
     *
     * @var int
     */
    public $paymentMethodId;

    /**
     * Total Order price.
     *
     * @var float
     */
    public $price;

    /**
     * Total Order Taxes price.
     *
     * @var float
     */
    public $taxes;

    /**
     * Form rules.
     *
     * @var array
     */
    protected $rules = [
        // Shipping
        'address.firstname' => 'required|string',
        'address.lastname' => 'required|string',
        'address.country' => 'required|string',
        'address.region' => 'string',
        'address.city' => 'required|string',
        'address.address' => 'required|string',
        'address.zip' => 'required',
        'address.phone' => 'required',
        // Invoice
        'invoiceAddress.vat' => '',
        'invoiceAddress.company' => 'string',
        'invoiceAddress.name' => 'string',
        'invoiceAddress.phone' => 'required_unless:invoiceAddressId,0',
        'invoiceAddress.country' => 'required_unless:invoiceAddressId,0|string',
        'invoiceAddress.address' => 'required_unless:invoiceAddressId,0|string',
        'invoiceAddress.region' => 'string',
        'invoiceAddress.city' => 'required_unless:invoiceAddressId,0|string',
        'invoiceAddress.zip' => 'required_unless:invoiceAddressId,0',
        // Others
        'shippingCarrierId' => 'required|exists:shipping_carriers,id',
        'paymentMethodId' => 'required|exists:payment_methods,id',
    ];

    public function mount()
    {
        if (empty(CartHelper::get())) {
            return redirect()->route('products');
        }

        $user = auth()->user();

        $this->address = new UserAddress;
        if ($user->addresses()->count()) {
            $this->address = $user->addresses()->orderBy('favorite', 'DESC')->first();
            $this->addressId = $this->address->id;
        }

        $this->invoiceAddress = new UserInvoiceAddress();
    }

    public function render()
    {
        $user = auth()->user();

        if ($this->addressId) {
            $this->address = $user->addresses()->where('id', $this->addressId)->first();
        }

        if (!$this->address->country) {
            $this->address->country = LocationHelper::getCountry();
        }
        if (!$this->invoiceAddress->country) {
            $this->invoiceAddress->country = LocationHelper::getCountry();
        }

        if (!$this->invoiceAddressId) {
            $this->showInvoiceForm = false;
        } elseif ($this->invoiceAddressId) {
            $this->showInvoiceForm = true;
            if ($this->invoiceAddressId >= 0) {
                $this->invoiceAddress = $user->invoiceAddresses()->where('id', $this->invoiceAddressId)->first();
            }
        }

        $this->setTaxByAddressCountry();
        $this->calculateTotalPrice();

        return view('livewire.shop.checkout')
            ->with('addresses', $user->addresses)
            ->with('invoiceAddresses', $user->invoiceAddresses)
            ->with('countries', Countries::all()->pluck('name.common', 'cca2'))
            ->with('shippingCarriers', ShippingCarrier::enabled()->get())
            ->with('paymentMethods', PaymentMethod::enabled()->get());
    }

    public function save()
    {
        $this->validate();

        if (!$this->addressId) {
            auth()->user()->addresses()->save($this->address);
        }

        if ($this->invoiceAddressId == -1) {
            auth()->user()->invoiceAddresses()->save($this->invoiceAddress);
        }

        $order = Order::create(
            auth()->user(),
            $this->address,
            $this->invoiceAddressId ? $this->invoiceAddress : null,
            ShippingCarrier::find($this->shippingCarrierId),
            PaymentMethod::find($this->paymentMethodId),
            TaxesHelper::getTaxRatio(),
            CartHelper::get()
        );

        redirect()->route('orders.pay', ['order' => $order]);
    }

    /**
     * Set the tax rate based on the introduced Invoice / Address country.
     *
     * @return void
     */
    public function setTaxByAddressCountry()
    {
        if ($this->invoiceAddressId != 0 && $this->invoiceAddress->country) {
            LocationHelper::setLocation($this->invoiceAddress->country);
        } elseif ($this->address->country) {
            LocationHelper::setLocation($this->address->country);
        }
    }

    /**
     * Calculate the total price of the order.
     */
    public function calculateTotalPrice()
    {
        $this->price = CartHelper::getTotalPrice();

        if ($this->shippingCarrierId) {
            $shippingCarrier = ShippingCarrier::find($this->shippingCarrierId);
            if ($shippingCarrier) {
                $this->price += TaxesHelper::calcPriceWithTax($shippingCarrier->price);
            }
        }

        if ($this->paymentMethodId) {
            $paymentMethod = PaymentMethod::find($this->paymentMethodId);
            if ($paymentMethod) {
                $this->price += TaxesHelper::calcPriceWithTax($paymentMethod->price);
            }
        }

        $this->taxes = TaxesHelper::calcTaxPrice($this->price);
    }
}
