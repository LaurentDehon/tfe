<div class="max-w-2xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Commentaires</h2>
    <div class="mb-8">
        <form wire:submit.prevent="postComment" class="bg-white p-6 rounded shadow space-y-4">
            @if($replyTo)
                <div class="text-sm text-gray-600 mb-2">
                    Répondre à un commentaire
                    <button type="button" wire:click="cancelReply" class="ml-2 text-blue-600 hover:underline">Annuler</button>
                </div>
            @endif
            <div>
                <input type="text" wire:model.defer="author" placeholder="Votre nom" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <textarea wire:model.defer="content" placeholder="Votre commentaire" class="w-full border rounded px-3 py-2" rows="3" required></textarea>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Envoyer</button>
            </div>
        </form>
    </div>
    <div>
        @if(isset($comments[null]))
            @foreach($comments[null] as $comment)
                @include('livewire.partials.comment', ['comment' => $comment, 'comments' => $comments])
            @endforeach
        @else
            <p class="text-center text-gray-400">Aucun commentaire.</p>
        @endif
    </div>
</div>