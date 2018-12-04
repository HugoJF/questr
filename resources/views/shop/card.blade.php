@component('components.item-cards.card', ['item' => $item])
    <ul class="list-unstyled mt-3 mb-4">
        <li>
            <h5>
                {{ ceil($item->price * config('app.durations')[1]['multiplier']) }} <i class="fas fa-coins"></i>
                <small class="text-muted"> / day</small>
            </h5>
            <h5>
                {{ ceil($item->price * 2 * config('app.durations')[2]['multiplier']) }} <i class="fas fa-coins"></i>
                <small class="text-muted"> / 2 days</small>
            </h5>
            <h3>
                {{ $item->price * 7 }} <i class="fas fa-coins"></i>
                <small class="text-muted"> / week</small>
            </h3>
        </li>
    </ul>
    <a data-toggle="modal" data-target="#buy-item-{{ $item->id }}" href="#" class="btn btn-lg btn-block btn-outline-primary">Buy</a>
@endcomponent

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
                    <ul class="mt-2 list-unstyled">
                        @foreach(config('app.durations') as $key => $dur)
                            <li><strong>{{ $dur['title'] }}:</strong> {{ ceil($item->price * intval($key) * $dur['multiplier']) }} <i class="fas fa-coins"></i>
                                <small class="ml-2 text-muted">({{ ceil($item->price * $dur['multiplier']) }} <i class="fas fa-coins"></i> per day)</small>
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
    
                    <label for="tag" class="control-label">
                        <strong>Weapon tag:</strong> 500
                        <i class="fas fa-coins"></i>
                        <small class="text-muted">(leave empty if you don't want a tag)</small>
                    </label>
                    <div class="form-group">
                        {!! Form::text('tag', null, ['class' => 'form-control', 'placeholder' => 'No weapon tag', 'size' => '32']) !!}
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