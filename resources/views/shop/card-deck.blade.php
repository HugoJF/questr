@foreach($items->chunk($chunkSize ?? 4) as $itemChunk)
    <div class="card-deck mb-3 text-center">
        @foreach($itemChunk as $item)
            @include('shop.card', compact($item))
        @endforeach
    </div>
@endforeach