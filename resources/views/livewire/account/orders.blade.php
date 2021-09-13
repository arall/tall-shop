<div class="space-y-6">
    <section>
        <div class="pt-6 bg-white shadow sm:rounded-md sm:overflow-hidden">
            <div class="inline-block w-full px-4 sm:px-6">
                <div class="float-left">
                    <h2 class="text-lg font-medium leading-6 text-gray-900">
                        {{ __('Orders') }}
                    </h2>
                </div>
            </div>
            <div class="flex flex-col mt-6">
                @if (count($orders))
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="text-xs font-medium tracking-wider text-left text-gray-500 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Id') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Price') }}
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 ">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $order->id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-order-status :order="$order" />
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @price($order->total_price)
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            <x-button.link-primary href="{{ route('order', ['order' => $order]) }}">
                                                {{ __('Details') }}
                                            </x-button.link-primary>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="m-10 text-center text-gray-800">
                        {{ __('You didn\'t order anything yet.') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
