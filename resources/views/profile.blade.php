@extends('layouts.app')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-2 pb-md-2 mx-auto text-center">
        <h1 class="display-4 mb-4">Profile</h1>
        <h3 class="font-weight-normal mb-1">{{ $user->name ?? $user->username }}</h3>
    </div>
    
    <div class="card-deck mb-3 justify-content-center">
        <p class="text-center">
            @if($user->avatar)
                <img src="{{ $user->avatar }}" class="rounded-circle shadow">
            @else
                <img src="https://via.placeholder.com/184" class="rounded-circle shadow">
            @endif
        </p>
    </div>
    
    <h3 class="pb-2">Quests progresses</h3>
    @include('questsProgresses.table', [
        'user'              => $user,
        'questsProgresses'  => $user->questProgresses()->latest()->with('quest')->get(),
    ])
    
    <h3 class="pt-5 pb-2">Transactions</h3>
    @include('transactions.table', [
        'transactions' => $user->transactions()->latest()->get()
    ])
@endsection