<table class="table">
    <thead>
    <tr>
        <th>Reason</th>
        <th>Type</th>
        <th>Value</th>
        <th>Date</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($transactions as $transaction)
        <tr>
            <!-- Reason -->
            <td>
                @php
                    $name = str_replace("App\\", "", $transaction->owner_type)
                @endphp
                @if($transaction->owner_type == 'App\Quest')
                    <a href="{{ route('quests.show', $transaction->owner) }}">{{ $name }}</a>
                @elseif($transaction->owner_type == 'App\Inventory')
                    <a href="{{ route('inventory.index') }}">{{ $name }}</a>
                @else
                    <a>{{ $name }}</a>
                @endif
            </td>
            
            <!-- Type -->
            @if($transaction->value < 0)
                <td><span class="badge badge-danger">Debit</span></td>
            @else
                <td><span class="badge badge-success">Credit</span></td>
            @endif
            
            <!-- Value -->
            <td>{{ $transaction->value }} <i class="fas fa-coins"></i></td>
            
            <!-- Date -->
            <td>{{ $transaction->created_at }}
                <small class="text-muted">({{ $transaction->created_at->diffForHumans() }})</small>
            </td>
        </tr>
    @empty
        <tr class="text-center">
            <td colspan="4">No progresses found!</td>
        </tr>
    @endforelse
    </tbody>
</table>