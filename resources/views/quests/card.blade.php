@php
    $status = null;
    
   
    if($quest->available || ($quest->inProgress && $quest->available)) {
        $status = 'primary';
    }
    
     if($quest->success && $quest->available) {
        $status = 'success';
    }
    
    if($quest->failed) {
        $status = 'danger';
    }
@endphp

<div class="card {{ isset($status) ? "border-$status" : '' }} mb-4 shadow-sm">
    <div class="card-header {{ isset($status) ? "bg-$status-faded border-$status" : '' }}">
        <h4 class="my-0 font-weight-normal w-100">
            <a class="{{ isset($status) ? "text-$status" : 'text-dark' }}" href="{{ route('quests.show', $quest) }}">{{ $quest->title }}</a>
        </h4>
        <br/>
    </div>
    @if($detailed ?? false)
        <div class="card-header {{ isset($status) ? "bg-$status-faded border-$status" : '' }}">
            <p class="w-100 my-0">{{ $quest->description }}</p>
        </div>
    @endif
    <div class="card-body">
        <h1 class="card-title pricing-card-title">{{ $quest->progress ?? 0 }}
            <small class="text-muted">/ {{ $quest->goal }}</small>
        </h1>
        <ul class="list-unstyled mt-3 mb-4">
            <li><strong>Duration:</strong> {{ $quest->endAt->diffForHumans(null, true) }}</li>
            <li><strong>Cost:</strong> {{ $quest->cost ?? 0}} <i class="fas fa-coins"></i></li>
            <li><strong>Starts:</strong> {{ $quest->startAt }}</li>
            <li><strong>Ends:</strong> {{ $quest->endAt }}</li>
            <li><h5>All members allowed</h5></li>
            <li class="my-3">
                @if($quest->locked)
                    <h3><span class="badge badge-dark">Unlock in: {{ $quest->startAt->diffForHumans(null, true) }}</span></h3>
                @elseif($quest->finished)
                    <h3><span class="badge badge-success">Rewarded</span></h3>
                @elseif($quest->success && $quest->available)
                    <h3><span class="badge badge-success">Completed</span></h3>
                @elseif($quest->available && $quest->inProgress)
                    <h3><span class="badge badge-primary">In progress</span></h3>
                @elseif($quest->available)
                    <h3><span class="badge badge-primary">Available</span></h3>
                @elseif($quest->failed)
                    <h3><span class="badge badge-danger">Failed</span></h3>
                @elseif($quest->expired)
                    <h3><span class="badge badge-dark">Expired</span></h3>
                @endif
            </li>
            <li><h3>{{ $quest->reward }} <i class="fas fa-coins"></i></h3></li>
        </ul>
        <!--
            Button states:
                1. LOCKED:      Quest is locked
                2. AVAILABLE:   Quest can be started
                3. IN PROGRESS: Quest is in progress
                4. SUCCESS:     Quest is finished and ready to complete
                5. FAILED:      Quest has ended and user did not complete
                6. EXPIRED:     Quest has ended and user did not start it
         -->
        @if($quest->locked)
            <a href="#" class="btn btn-lg btn-block btn-outline-dark text-black-50 disabled">Locked</a>
        @elseif($quest->finished)
            <a href="#" class="btn btn-lg btn-block btn-success shadow-sm disabled">Finished</a>
        @elseif($quest->success && $quest->available)
            <a href="{{ route('quests.finish', $quest) }}" class="btn btn-lg btn-block btn-success shadow-sm">Finish</a>
        @elseif($quest->inProgress && $quest->available)
            <a href="{{ route('quests.show', $quest) }}" class="btn btn-lg btn-block btn-primary shadow-sm">View details</a>
        @elseif($quest->available)
            <a href="{{ route('quests.start', $quest) }}" class="btn btn-lg btn-block btn-outline-primary shadow-sm">Start</a>
        @elseif($quest->failed)
            <a href="#" class="btn btn-lg btn-block btn-danger disabled">Failed</a>
        @elseif($quest->expired)
            <a href="#" class="btn btn-lg btn-block btn-outline-dark text-black-50 disabled">Expired</a>
        @endif
    </div>
    @if($detailed ?? false)
        <div class="card-footer">
            <h4>Filters:</h4>
            <h5>
                @foreach($quest->questFilters as $filter)
                    <a style="cursor: pointer" data-toggle="modal" data-target="#quest-filter-{{ $filter->id }}"><span class="badge badge-dark">{{ $filter->key }}: {{ $filter->value }}</span></a>
                    @push('modals')
                        <div class="modal fade" id="quest-filter-{{ $filter->id }}" tabindex="-1" role="dialog" aria-labelledby="quest-filter-label-{{ $filter->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="quest-filter-label-{{ $filter->id }}">Delete quest filter</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        You are about to delete the quest filter <strong>{{ $filter->key }}: {{ $filter->value }}</strong>.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        {!! Form::open(['route' => ['quest-filters.delete', $filter], 'method' => 'DELETE']) !!}
                                        <button class="btn btn-xs btn-danger btn-form-fix" type="submit">Delete</button>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endpush
                @endforeach
                <a href="{{ route('quests.filters.create', $quest) }}"><span class="badge badge-dark">+</span></a>
            </h5>
        </div>
    @endif
</div>
