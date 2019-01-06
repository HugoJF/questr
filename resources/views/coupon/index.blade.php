@extends('layouts.app')

@section('content')
    <h1 class="py-3">Coupon list</h1>
    <table class="table table-sm">
        <thead>
        <tr>
            <th>Code</th>
            <th>Reward</th>
            <th>Status</th>
            <th>Uses</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($coupons as $coupon)
            <tr>
                <!-- Code -->
                <td><code>{{ $coupon->code }}</code></td>
                
                <!-- Reward -->
                <td>{{ $coupon->reward }} <i class="fas fa-coins"></i></td>
                
                <!-- Status -->
                @if($coupon->startAt->isFuture())
                    <td><span class="badge badge-dark">Locked</span></td>
                @elseif($coupon->endAt->isPast())
                    <td><span class="badge badge-danger">Expired</span></td>
                @else
                    <td><span class="badge badge-success">Valid</span></td>
                @endif
                
                <!-- Uses -->
                <td>{{ $coupon->userUses()->count() }}</td>
                <!-- Actions -->
                <td>
                    <a class="btn btn-sm btn-primary" href="{{ route('coupon.edit', $coupon) }}">Edit</a>
                    {!! Form::open(['route' => ['coupon.delete', $coupon], 'method' => 'DELETE', 'style' => 'display: inline;']) !!}
                    <button class="btn btn-sm btn-danger">Delete</button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center" colspan="5">No coupons generated yet!</td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection