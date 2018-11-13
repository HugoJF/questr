<div class="card mb-4 shadow-sm">
    <div class="card-header">
        <h4 class="my-0 font-weight-normal w-100">
            <a class="text-dark" href="#">{{ $item->item->market_hash_name }}</a>
        </h4>
        <br/>
    </div>
    <div class="card-body">
        <img class="{{ ($detailed ?? false) ? 'w-auto' : 'w-100' }}" src="{{ $item->item->icon_url }}">
        <ul class="list-unstyled mt-3 mb-4">
            <li>
                <h3>{{ $item->ends_at->diffForHumans(null, true) }} remaining</h3>
            </li>
        </ul>
        @if($item->expired)
            <a href="#" class="btn btn-lg btn-block btn-outline-dark disabled">Expired</a>
        @elseif($item->equipped)
            <a href="#" class="btn btn-lg btn-block btn-primary disabled">Equipped</a>
        @else
            <a href="{{ route('inventory.equip', $item) }}" class="btn btn-lg btn-block btn-outline-primary">Equip</a>
        @endif
    </div>
</div>
