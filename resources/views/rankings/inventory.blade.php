@extends('layouts.app')

@section('content')
    <ul>
        @foreach ($ranking as $user)
            <li>{{ $loop->index + 1}}. {{ $user->name ?? $user->username}}: {{ $user->inventories()->count() }}</li>
        @endforeach
    </ul>
@endsection