<div>
    <!-- Formulaire pour ajouter un nouveau commentaire -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ajouter un commentaire</h3>
        
        <form wire:submit.prevent="addComment">
            <div class="mb-4">
                <label for="author_name" class="block text-sm font-medium text-gray-700">Votre nom</label>
                <input type="text" id="author_name" wire:model="newComment.author_name" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4">
                @error('newComment.author_name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Commentaire</label>
                <textarea id="content" rows="4" wire:model="newComment.content" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4"></textarea>
                @error('newComment.content') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                    Publier le commentaire
                </button>
            </div>
        </form>
    </div>
    
    <!-- Liste des commentaires -->
    <div class="space-y-6">
        <h3 class="text-lg font-semibold text-gray-900">Commentaires ({{ count($comments) }})</h3>
        
        @forelse($comments as $comment)
            @livewire('comment-item', ['comment' => $comment], key('comment-'.$comment->id))
        @empty
            <div class="bg-white p-6 rounded-lg shadow-sm text-gray-500 text-center">
                <p>Aucun commentaire pour le moment.</p>
                <p class="mt-2">Soyez le premier à partager votre avis!</p>
            </div>
        @endforelse
    </div>
</div>
