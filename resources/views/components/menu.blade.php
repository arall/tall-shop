<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="flex items-center flex-shrink-0">
                    <img class="block w-auto h-8 lg:hidden"
                        src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow">
                    <img class="hidden w-auto h-8 lg:block"
                        src="https://tailwindui.com/img/logos/workflow-logo-indigo-600-mark-gray-800-text.svg"
                        alt="Workflow">
                </div>
                <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                    <!-- Current: "border-indigo-500 text-gray-900", Default: "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700" -->
                    <a href="{{ route('products') }}"
                        class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:border-gray-300 hover:text-gray-700">
                        {{ __('Products') }}
                    </a>

                </div>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                <div class="mr-5">
                    @livewire('shop.cart-icon')
                </div>

                <!-- Profile dropdown -->
                <div x-data="{ open: false }" @keydown.escape.stop="open = false" @click.away="open = false"
                    class="relative ml-3">
                    <div>
                        @auth
                            <button type="button"
                                class="flex items-center max-w-xs text-sm bg-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                id="user-menu" @click="open = !open" aria-haspopup="true" x-bind:aria-expanded="open">
                                <span class="sr-only">
                                    {{ __('Open user menu') }}
                                </span>
                                <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->gravatar }}">
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="text-base font-medium text-gray-500 hover:text-gray-900">
                                {{ __('Sing in') }}
                            </a>
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center px-4 py-2 ml-8 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                                {{ __('Sing up') }}
                            </a>
                        @endauth
                    </div>

                    @auth
                        <div x-description="Dropdown menu, show/hide based on menu state." x-show="open"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 w-48 py-1 mt-2 origin-top-right bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu" style="display: none;">

                            <a href="{{ route('account') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                {{ __('Your Account') }}
                            </a>

                            <a href="{{  route('orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                {{ __('Your Orders') }}
                            </a>

                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                                {{ __('Sign out') }}
                            </a>

                        </div>
                    @else
                    @endauth

                </div>
            </div>
            <div class="flex items-center -mr-2 sm:hidden">
                <div class="mr-5">
                    @livewire('shop.cart-icon')
                </div>
                <!-- Mobile menu button -->
                <button type="button"
                    class="inline-flex items-center justify-center p-2 text-gray-400 bg-white rounded-md hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    aria-controls="mobile-menu" @click="open = !open" aria-expanded="false"
                    x-bind:aria-expanded="open.toString()">
                    <span class="sr-only">
                        {{ __('Open user menu') }}
                    </span>
                    <svg x-state:on="Menu open" x-state:off="Menu closed" class="block w-6 h-6"
                        :class="{ 'hidden': open, 'block': !(open) }" x-description="Heroicon name: outline/menu"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg x-state:on="Menu open" x-state:off="Menu closed" class="hidden w-6 h-6"
                        :class="{ 'block': open, 'hidden': !(open) }" x-description="Heroicon name: outline/x"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-description="Mobile menu, show/hide based on menu state." class="sm:hidden" id="mobile-menu" x-show="open"
        style="display: none;">
        <div class="pt-2 pb-3 space-y-1">

            <!-- Current: "bg-indigo-50 border-indigo-500 text-indigo-700", Default: "border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800" -->
            <a href="{{ route('products') }}"
                class="block py-2 pl-3 pr-4 text-base font-medium text-gray-600 border-l-4 border-transparent hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800">
                {{ __('Products') }}
            </a>

        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            @auth
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <img class="w-10 h-10 rounded-full" src="{{ auth()->user()->gravatar }}">
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-sm font-medium text-gray-500">
                            {{ auth()->user()->email }}
                        </div>
                    </div>
                </div>
            @endauth
            <div class="mt-3 space-y-1">
                @auth
                    <a href="{{ route('account') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        {{ __('Your Account') }}
                    </a>

                    <a href="{{  route('orders') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        {{ __('Your Orders') }}
                    </a>
                @else
                    <a href="{{ route('register') }}"
                        class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        {{ __('Sing up') }}
                    </a>
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100">
                        {{ __('Sing in') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>
    @auth
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endauth
</nav>
