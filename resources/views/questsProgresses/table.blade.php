<table class="table">
    <thead>
    <tr>
        <th>Quest title</th>
        <th>Cost</th>
        <th>Progress</th>
        <th>Reward</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($questsProgresses as $progress)
        <tr>
            <!-- Quest Title -->
            <td><a href="{{ route('quests.show', $progress->quest) }}">{{ $progress->quest->title }}</a></td>
            
            <!-- Cost -->
            <td>{{ $progress->quest->cost }} <i class="fas fa-coins"></i></td>
            
            <!-- Progress -->
            <td>{{ $progress->progress }}
                <small class="text-muted">/ {{ $progress->quest->goal }}</small>
                
            </td>
            
            <!-- Reward -->
            <td>{{ $progress->quest->reward }} <i class="fas fa-coins"></i></td>
            
            <!-- Status -->
            <td>@include('quests.badge', ['user' => $progress->user, 'quest' => $progress->quest])</td>
        </tr>
    @empty
        <tr class="text-center">
            <td colspan="5">No progresses found!</td>
        </tr>
    @endforelse
    </tbody>
</table>