@extends('layouts.app')

@section('content')
    <h1 class="text-center">Users</h1>
    
    <p class="lead text-center mb-4">List of registered users!</p>
    
    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Quests</th>
            <th>Balance</th>
            <th>Items</th>
            <th>Transactions</th>
            <th>Coupons</th>
            <th>Register date</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($users as $user)
            <tr>
                <!-- User name -->
                <td>
                    <a href="{{ route('profile', $user) }}">{{ $user->username ?? $user->name }}</a>
                    @if($user->name)
                        <small class="text-muted">{{ $user->name }}</small>
                    @endif
                </td>
                
                <!-- Quest progresses -->
                <td>{{ $user->questProgresses()->count() }}</td>
                
                <!-- Balance -->
                <td>{{ $user->balance }} <i class="fas fa-coins"></i></td>
                
                <!-- Item count -->
                <td>{{ $user->inventories()->count() }}</td>
                
                <!-- Transactions -->
                <td>{{ $user->transactions()->count() }}</td>
                
                <!-- Coupon uses -->
                <td>{{ $user->couponUses()->count() }}</td>
                
                <!-- Register date -->
                <td>{{ $user->created_at }}
                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                </td>
            </tr>
        @empty
            <tr class="text-center">
                <td colspan="1">No users found!</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection