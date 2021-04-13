<div class="space-y-6">
    <section>
        <div class="pt-6 bg-white shadow sm:rounded-md sm:overflow-hidden">
            <div class="inline-block w-full px-4 sm:px-6">
                <div class="float-left">
                    <h2 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Order') }} #{{ $order->id }}
                    </h2>
                </div>
            </div>
            <div class="flex flex-col mt-6">
                <div class="px-4 py-5 border-t border-gray-200 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{  __('Status') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <x-order-status :order="$order"/>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Date') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->created_at->format('jS F Y') }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Payment Method') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->paymentMethod->name }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{  __('Shipping Carrier') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->shippingCarrier->name }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ __('Shipping Information') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->firstname }} {{ $order->lastname }}<br>
                                {{ $order->phone }}
                                {{ $order->address }}<br>
                                {{ $order->city }}, {{ $order->region }}, {{ $order->ip }}<br>
                                {{ $order->country }}
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">
                                {{  __('Total Price') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @price($order->price)
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

                                    @foreach($order->orderProducts as $orderProduct)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    {{ $orderProduct->product->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    {{ implode(', ', $orderProduct->variants) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    {{ $orderProduct->units }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    @price($orderProduct->unit_price)
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    @price($orderProduct->price)
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
