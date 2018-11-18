@extends('layouts.app')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-3 pb-md-5 mx-auto text-center">
        <h1 class="display-4">Shop</h1>
        <p class="lead">Items available to buy!</p>
    </div>
    
    @include('shop.card-deck', ['items' => $items, 'chunkSize' => 4])
    
    <div class="text-center">
        {!! $items->links() !!}
    </div>
@endsection