@extends('layouts.app')

@section('content')
    <div class="jumbotron">
        <h1 class="display-4">Welcome {{ Auth::check() ? ', ' . (Auth::user()->name ?? Auth::user()->username) : 'to Questr' }}!</h1>
        <p class="lead">
            This is our brand new quest system that will allow non-VIP players to change skins, knives and gloves by completing quests and challenges in our CS:GO servers!
            <br>
            You can also compete with other players in order to receive exclusives coupons, discount codes, VIP codes, coin bonus and other stuff!
        </p>
        <hr class="my-4">
        <p>If you are not sure how Questr works, we have a guide publish on our blog, check it out!</p>
        <a class="btn btn-primary btn-lg" href="https://denerdtv.com/questr/" role="button">More information</a>
    </div>
    
    <div class="pricing-header px-3 py-3 pt-md-2 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Random quests</h1>
        <p class="lead">Random selected quests!</p>
    </div>
    @include('quests.card-deck', ['quests' => $randomQuests])
@endsection
