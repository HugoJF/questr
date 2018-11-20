@component('components.item-cards.card', ['item' => $item->item])
    <ul class="list-unstyled mt-3 mb-4">
        <li>
            <h4>{{ $item->ends_at->diffForHumans(null, true) }} remaining</h4>
        </li>
    </ul>
    @if($item->expired)
        <a href="#" class="shadow-sm btn btn-lg btn-block btn-outline-dark disabled">Expired</a>
    @elseif($item->equipped)
        <a href="#" class="btn btn-lg btn-block btn-primary disabled">Equipped</a>
    @else
        <a href="{{ route('inventory.equip', $item) }}" class="shadow-sm btn btn-lg btn-block btn-outline-primary">Equip</a>
    @endif
@endcomponent