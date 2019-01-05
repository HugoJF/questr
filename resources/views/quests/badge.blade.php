@if($quest->locked)
    <span class="badge badge-dark">Unlock in: {{ $quest->startAt->diffForHumans(null, true) }}</span>
@elseif($quest->finished)
    <span class="badge badge-success">Rewarded</span>
@elseif($quest->success && $quest->available)
    <span class="badge badge-success">Completed</span>
@elseif($quest->available && $quest->inProgress)
    <span class="badge badge-primary">In progress</span>
@elseif($quest->available)
    <span class="badge badge-primary">Available</span>
@elseif($quest->failed)
    <span class="badge badge-danger">Failed</span>
@elseif($quest->expired)
    <span class="badge badge-dark">Expired</span>
@endif