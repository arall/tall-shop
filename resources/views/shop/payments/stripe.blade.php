@extends('layouts.app')

@section('content')
    <script type="text/javascript">
        window.onload = function() {
            var stripe = Stripe('{{ config('services.stripe.public') }}');
            stripe.redirectToCheckout({ sessionId: '{{ $sessionId }}' });
        };
    </script>
@endsection

@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
@endpush
