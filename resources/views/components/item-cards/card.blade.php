<div class="card mb-4 shadow-sm quality-color color-{{ strtolower($item->quality_color) }}">
    <div class="card-header">
        <h4 class="my-0 font-weight-normal w-100">
            <a class="text-white" href="{{ route('shop.show', $item) }}">{{ $item->market_hash_name }}</a>
        </h4>
        <br/>
    </div>
    <div class="card-body">
        <img class="{{ ($detailed ?? false) ? 'w-auto' : 'w-100' }}" src="{{ $item->icon_url }}">
        {{ $slot }}
    </div>
</div>