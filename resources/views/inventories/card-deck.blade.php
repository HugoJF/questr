@forelse($items->chunk($chunkSize ?? 4) as $itemChunk)
    <div class="card-deck mb-3 text-center">
        @foreach($itemChunk as $item)
            @include('inventories.card', compact($item))
        @endforeach
    </div>
@empty
    <h2 class="text-center">Looks like your inventory is empty! Buy some items in our <a href="{{ route('shop.index') }}">shop</a>.</h2>
@endforelse