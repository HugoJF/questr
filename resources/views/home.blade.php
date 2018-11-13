@extends('layouts.app')

@section('content')
    
        <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
            <h1 class="display-4">Top quests</h1>
            <p class="lead">Available quests now!</p>
        </div>
        @include('quests.card-deck', ['quests' => $randomQuests])
        
@endsection