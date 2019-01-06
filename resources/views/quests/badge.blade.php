@php
    $user = $user ?? null;
@endphp
@if($quest->locked)
    <span class="badge badge-dark">Unlock in: {{ $quest->startAt->diffForHumans(null, true) }}</span>
@elseif($quest->finished($user))
    <span class="badge badge-success">Rewarded</span>
@elseif($quest->success($user) && $quest->available)
    <span class="badge badge-success">Completed</span>
@elseif($quest->available && $quest->inProgress($user))
    <span class="badge badge-primary">In progress</span>
@elseif($quest->available)
    <span class="badge badge-primary">Available</span>
@elseif($quest->failed($user))
    <span class="badge badge-danger">Failed</span>
@elseif($quest->expired)
    <span class="badge badge-dark">Expired</span>
@endif