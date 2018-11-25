@forelse($quests->chunk($chunkSize ?? 4) as $questChunk)
    <div class="card-deck mb-3 text-center">
        @foreach($questChunk as $quest)
            @include('quests.card', compact($quest))
        @endforeach
    </div>
@empty
    <h2 class="text-center">There are no quests available right now. Check back later!</h2>
@endforelse