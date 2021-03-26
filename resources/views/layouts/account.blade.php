@extends('layouts.base')

@section('body')
    <main class="max-w-7xl mx-auto pb-10 lg:py-12 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
            @include('livewire.account.partials.menu')

            <div class="sm:px-6 lg:px-0 lg:col-span-9">
                @yield('content')

                @isset($slot)
                    {{ $slot }}
                @endisset
            </div>
        </div>
    </main>
@endsection

