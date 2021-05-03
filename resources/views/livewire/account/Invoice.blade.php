<div class="space-y-6">
    <section>
        <div class="pt-6 bg-white shadow sm:rounded-md sm:overflow-hidden">
            <div class="inline-block w-full px-4 sm:px-6">
                <div class="float-left">
                    <h2 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Invoice') }} {{ $invoice->number }}
                    </h2>
                </div>
            </div>
            <div class="flex flex-col mt-6">
                <div class="px-4 py-5 border-t border-gray-200 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Date') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $invoice->created_at->format('jS F Y') }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Information') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $invoice->vat }} {{ $invoice->name }}<br>
                                {{ $invoice->phone }}
                                {{ $invoice->address }}<br>
                                {{ $invoice->city }}, {{ $invoice->region }}, {{ $invoice->ip }}<br>
                                {{ $invoice->country }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Tax') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @price($invoice->total_price - $invoice->total_price_untaxed) ({{ $invoice->tax * 100 }}%)
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Total Price') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @price($invoice->total_price)
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dd class="mt-1">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                {{ __('Product') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                {{ __('Options') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                {{ __('Units') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                {{ __('Price Unit') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                {{ __('Total Price') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm text-gray-900 bg-white divide-y divide-gray-200">

                                        @foreach($invoice->invoiceProducts as $invoiceProduct)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    {{ $invoiceProduct->product_name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    {{ implode(', ', $invoiceProduct->variants) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    {{ $invoiceProduct->units }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    @price($invoiceProduct->unit_price + $invoiceProduct->unit_price * $invoice->tax)
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    @price($invoiceProduct->price + $invoiceProduct->price * $invoice->tax)
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                        </ul>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </section>
</div>
