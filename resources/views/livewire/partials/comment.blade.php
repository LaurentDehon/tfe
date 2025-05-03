<div class="mb-4 border rounded bg-gray-50 p-4">
    <div class="flex items-center justify-between">
        <div class="font-semibold text-blue-700">{{ $comment->author }}</div>
        <div class="text-xs text-gray-400">{{ $comment->created_at->format('d/m/Y H:i') }}</div>
    </div>
    <div class="mt-2 text-gray-800 whitespace-pre-line">{{ $comment->content }}</div>
    <div class="flex items-center gap-4 mt-2">
        <button wire:click="setReply({{ $comment->id }})" class="text-sm text-blue-600 hover:underline">Répondre</button>
        <button wire:click="vote({{ $comment->id }}, 'up')" class="text-green-600 hover:text-green-800 flex items-center text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"/></svg>
            {{ $comment->votes_up }}
        </button>
        <button wire:click="vote({{ $comment->id }}, 'down')" class="text-red-600 hover:text-red-800 flex items-center text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
            {{ $comment->votes_down }}
        </button>
    </div>
    @if(isset($comments[$comment->id]))
        <div class="ml-6 mt-4 border-l-2 border-blue-100 pl-4">
            @foreach($comments[$comment->id] as $child)
                @include('livewire.partials.comment', ['comment' => $child, 'comments' => $comments])
            @endforeach
        </div>
    @endif
</div>
