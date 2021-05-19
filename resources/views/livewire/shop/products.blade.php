<div>
    <div class="container px-6 mx-auto">
        <div class="relative max-w-lg mx-auto">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                    <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
            <input wire:model="search" class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" type="text" placeholder="Search">
        </div>
    </div>
    <div class="container px-6 mx-auto my-8">

        <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

        @foreach($products as $product)
            <div class="w-full max-w-sm mx-auto overflow-hidden rounded-md shadow-md" href="{{ route('product', $product) }}" >
                <a href="{{ route('product', $product) }}">
                    <img class="w-full" src="{{ $product->getCoverUrl('mid') }}">
                </a>

                <div class="relative">
                    <button wire:click="addToCart({{ $product->id }})" class="absolute top-0 float-right p-2 -mt-5 text-white bg-blue-600 rounded-full right-3 hover:bg-blue-500 focus:outline-none focus:bg-blue-500">
                        @php // @todo Fix the circle heigh
                        @endphp
                        <svg wire:loading wire:target="addToCart({{ $product->id }})" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg wire:loading.remove wire:target="addToCart({{ $product->id }})" class="w-5 h-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </button>
                </div>

                <div class="px-5 py-3">
                    <a href="{{ route('product', $product) }}">
                        <h3 class="text-gray-700 uppercase">
                            {{ $product->name }}
                        </h3>
                    </a>
                    <span class="mt-2 text-gray-500">
                        <x-product.price :price="$product->getMinPrice()"/>
                    </span>
                </div>
            </div>
        @endforeach
        </div>

        <div class="flex items-center justify-between mt-4 mb-4">
            <div class="flex-1 pr-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
