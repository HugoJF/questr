@extends('layouts.app')

@section('content')
    <h1 class="text-center mb-3">{{ $runner::getTitle() }}</h1>
    
    <ul class="text-center list-unstyled">
        @foreach ($ranking as $user)
            <li>
                <p><strong>{{ $user->name ?? $user->username }}</strong>: {{ $user->balance }} <i class="fas fa-coins"></i></p>
                @if($user->avatar)
                    <p class="text-center"><img width="150" class="my-2 rounded-circle shadow-sm" src="{{ $user->avatar }}"></p>
                @else
                    <p class="text-center"><img width="150" class="my-2 rounded-circle shadow-sm" src="https://via.placeholder.com/150"></p>
                @endif
            </li>
        @endforeach
    </ul>
@endsection