@component('components.item-cards.card', ['item' => $item->item])
    <ul class="list-unstyled mt-3 mb-4">
        <li>
            <h5>Float: {{ $item->float }}</h5>
            @if($item->ends_at->isFuture())
                <h4>{{ $item->ends_at->diffForHumans(null, true) }} remaining</h4>
            @else
                <h4>Expired</h4>
            @endif
        </li>
    </ul>
    @if($item->expired)
        <a href="#" class="text-white shadow-sm btn btn-lg btn-block btn-outline-dark disabled">Expired</a>
    @elseif($item->equipped && $item->synced)
        <a href="#" class="btn btn-lg btn-block btn-primary disabled">Equipped</a>
    @elseif($item->equipped && !$item->synced)
        <span tabindex="0" data-toggle="tooltip" title="Item is not yet synchronized with our game-servers. Please wait a few seconds...">
            <a
                href="#"
                class="btn btn-lg btn-block btn-primary disabled"
                data-placement="bottom"
                style="pointer-events: none;"
            >Waiting sync...</a>
        </span>
    @else
        <a href="{{ route('inventory.equip', $item) }}" class="shadow-sm btn btn-lg btn-block btn-outline-primary">Equip</a>
    @endif
@endcomponent