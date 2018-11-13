@extends('layouts.app')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-3 pb-md-5 mx-auto text-center">
        <h1 class="display-4">Quests</h1>
        <ul class="nav nav-pills my-3 justify-content-center">
            <li class="nav-item">
                <a class="nav-link active" href="#">Available</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Failed</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Locked</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Expired</a>
            </li>
        </ul>
        <p class="lead">Available quests now!</p>
    </div>
    
    @include('quests.card-deck', ['quests' => $quests, 'chunkSize' => 3])
    
    <div class="text-center">
        {!! $quests->links() !!}
    </div>
@endsection