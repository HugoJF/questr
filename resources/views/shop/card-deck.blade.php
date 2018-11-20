@forelse($items->chunk($chunkSize ?? 4) as $itemChunk)
    <div class="card-deck mb-3 text-center">
        @foreach($itemChunk as $item)
            @include('shop.card', compact($item))
        @endforeach
    </div>
@empty
    @if(request()->has('query'))
        <h2 class="text-center">Could not find any items with <code>{{ request()->input('query') }}</code></h2>
    @else
        <h2 class="text-center">Oops, something is not right.</h2>
    @endif
@endforelse