@extends('layouts.app')

@section('content')
    
        <div class="pricing-header px-2 py-2 pt-md-5 pb-md-4 mx-auto text-center">
            <h1 class="display-4">Top quests</h1>
            <p class="lead">Random selected quests!</p>
        </div>
        @include('quests.card-deck', ['quests' => $randomQuests])
        
@endsection
