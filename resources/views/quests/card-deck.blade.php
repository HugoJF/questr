@foreach($quests->chunk($chunkSize ?? 4) as $questChunk)
    <div class="card-deck mb-3 text-center">
        @foreach($questChunk as $quest)
            @include('quests.card', compact($quest))
        @endforeach
    </div>
@endforeach