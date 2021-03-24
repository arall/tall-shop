<!-- This example requires Tailwind CSS v2.0+ -->
<div x-data="{ open: false }"
    class="relative bg-white">
    <div class="flex justify-between items-center px-4 py-6 sm:px-6 md:justify-start md:space-x-10">
        <div>
            <a href="/" class="flex">
                <span class="sr-only">Workflow</span>
                <img class="h-8 w-auto sm:h-10" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg"
                    alt="">
            </a>
        </div>

        <!-- Mobile -->
        <div class="-mr-2 -my-2 md:hidden flex">

            <div class="mr-5">
                @livewire('shop.cart-icon')
            </div>

            <button type="button"
                @click="open = true"
                @mousedown="if (open) $event.preventDefault()"
                class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                aria-expanded="false">
                <span class="sr-only">{{ __('Open Menu') }}</span>
                <!-- Heroicon name: outline/menu -->
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Non-mobile -->
        <div class="hidden md:flex-1 md:flex md:items-center md:justify-between">
            <nav class="flex space-x-10">
                <a href="{{ route('products') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">
                    {{ __('Products') }}
                </a>
            </nav>
            <div class="flex items-center md:ml-12">

                <div class="mr-5">
                    @livewire('shop.cart-icon')
                </div>

                @auth
                    <a
                        href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="text-base font-medium text-gray-500 hover:text-gray-900"
                    >
                        {{ __('Log out') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">
                            {{ __('Sing in') }}
                        </a>
                    <a href="{{ route('register') }}"
                        class="ml-8 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        {{ __('Sing up') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile -->
    <div
        x-show="open"
        x-transition:enter="duration-200 ease-out"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:leave="duration-100 ease-in"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-description="Mobile menu, show/hide based on mobile menu state."
        @click.away="open = false"
        style="display: none;"
        class="absolute top-0 inset-x-0 p-2  z-10 transition transform origin-top-right md:hidden">
        <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y-2 divide-gray-50">
            <div class="pt-5 pb-6 px-5">
                <div class="flex items-center justify-between">
                    <div>
                        <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg"
                            alt="Workflow">
                    </div>
                    <div class="-mr-2">
                        <button type="button"
                            @click="open = false"
                            class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                            <span class="sr-only">{{ __('Close Menu') }}</span>
                            <!-- Heroicon name: outline/x -->
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="mt-6">
                    <nav class="grid gap-6">
                        <a href="{{ route('products') }}" class="-m-3 p-3 flex items-center rounded-lg hover:bg-gray-50">
                            {{ __('Products') }}
                        </a>
                    </nav>
                </div>
            </div>
            <div class="py-6 px-5">
                <div class="mt-6">
                    @auth
                        <a
                            href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="text-indigo-600 hover:text-indigo-500"
                        >
                            {{ __('Log out') }}
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                            class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Sing up')}}
                        </a>
                        <p class="mt-6 text-center text-base font-medium text-gray-500">
                            {{ __('Existing customer?')}}
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500">
                                {{ __('Sing in') }}
                            </a>
                        </p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@auth
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endauth
