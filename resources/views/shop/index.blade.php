@extends('layouts.app')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-3 pb-md-5 mx-auto text-center">
        <h1 class="display-4">Shop</h1>
        <p class="lead">Items available to buy!</p>
    </div>


    {!! Form::open(['route' => ['shop.index'], 'method' => 'GET']) !!}
    
    <div class="form-group mb-5">
        <input class="form-control" name="query" type="text" value="{{ request()->input('query') }}" placeholder="Filter item by name...">
    </div>
    
    {!! Form::close() !!}
    
    @include('shop.card-deck', ['items' => $items, 'chunkSize' => 4])
    
    <div class="text-center">
        {!! $items->appends(request()->query())->links() !!}
    </div>
@endsection