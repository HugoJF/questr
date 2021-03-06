@extends('layouts.app')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-2 pb-md-2 mx-auto text-center">
        <h1 class="display-4">Item details</h1>
    </div>
    
    <div class="card-deck mb-3 text-center">
        @include('inventories.card', ['item' => $item, 'detailed' => true])
    </div>
@endsection