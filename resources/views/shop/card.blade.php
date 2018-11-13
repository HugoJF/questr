<div class="card mb-4 shadow-sm">
    <div class="card-header">
        <h4 class="my-0 font-weight-normal w-100">
            <a class="text-dark" href="{{ route('shop.show', $item) }}">{{ $item->market_hash_name }}</a>
        </h4>
        <br/>
    </div>
    <div class="card-body">
        <img class="{{ ($detailed ?? false) ? 'w-auto' : 'w-100' }}" src="{{ $item->icon_url }}">
        <ul class="list-unstyled mt-3 mb-4">
            <li>
                <h3>
                    {{ $item->price * 7 }} <i class="fas fa-coins"></i>
                    <small class="text-muted"> / week</small>
                </h3>
            </li>
        </ul>
        <a data-toggle="modal" data-target="#buy-item-{{ $item->id }}" href="#" class="btn btn-lg btn-block btn-outline-primary">Buy</a>
    </div>
</div>

@push('modals')
    <div class="modal fade" id="buy-item-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="buy-item-label-{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(['route' => ['shop.buy', $item], 'method' => 'POST']) !!}
                <div class="modal-header">
                    <h5 class="modal-title" id="buy-item-label-{{ $item->id }}">Delete quest filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to buy <strong>{{ $item->market_hash_name }}</strong>?
                    <ul class="list-unstyled">
                        @foreach(config('app.durations') as $key => $dur)
                            <li><strong>{{ $dur['title'] }}:</strong> {{ ceil($item->price * intval($key) * $dur['multiplier']) }} <i class="fas fa-coins"></i>
                                <small class="ml-2 text-muted">({{ $item->price * $dur['multiplier'] }} <i class="fas fa-coins"></i> per day)</small>
                            </li>
                        @endforeach
                    </ul>
                    @php
                        $durations = [];
                        foreach (config('app.durations') as $key => $dur) {
                            $durations[$key] = $dur['title'];
                        }
                    @endphp
                    <div class="form-group">
                        {!! Form::select('duration', $durations, null, ['class' => 'my-3 form-control']) !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-xs btn-success" type="submit">Yes</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endpush