@extends('layouts.app')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-3 pb-md-3 mx-auto text-center">
        <h1 class="display-4">Inventory</h1>
        <p class="lead">Items bought!</p>
    </div>
    
    @include('inventories.card-deck', ['items' => $items, 'chunkSize' => 4])
    
    <div class="text-center">
        {!! $items->appends(request()->query())->links() !!}
    </div>
@endsection