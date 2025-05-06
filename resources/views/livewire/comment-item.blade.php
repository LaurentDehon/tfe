<div class="bg-white p-6 rounded-lg shadow-sm">
    <div class="flex justify-between">
        <div class="flex items-center mb-4">
            <div class="bg-blue-100 rounded-full p-2 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900">{{ $comment->author_name }}</h4>
                <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-2 text-sm">
            <button wire:click="voteUp" class="flex items-center text-gray-500 hover:text-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                </svg>
                <span class="ml-1">{{ $comment->votes_up }}</span>
            </button>
            
            <button wire:click="voteDown" class="flex items-center text-gray-500 hover:text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z" />
                </svg>
                <span class="ml-1">{{ $comment->votes_down }}</span>
            </button>
            
            {{-- Bouton de suppression visible uniquement pour les administrateurs --}}
            @if($isAdmin)
            <button 
                onclick="confirmDelete({{ $comment->id }}, 'comment')" 
                class="ml-4 flex items-center text-gray-500 hover:text-red-600" 
                title="Supprimer ce commentaire"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
            @endif
        </div>
    </div>
    
    <div class="prose prose-blue max-w-none">
        <p>{{ $comment->content }}</p>
    </div>
    
    <div class="mt-4 pt-4 border-t border-gray-100">
        <button wire:click="toggleReplyForm" class="text-sm text-blue-600 hover:text-blue-800">
            {{ $showReplyForm ? 'Annuler la réponse' : 'Répondre' }}
        </button>
        
        @if($showReplyForm)
            <form wire:submit.prevent="addReply" class="mt-4 bg-gray-50 p-4 rounded-lg">
                <div class="mb-4">
                    <label for="replyAuthorName" class="block text-sm font-medium text-gray-700">Votre nom</label>
                    <input type="text" id="replyAuthorName" wire:model="replyAuthorName" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4">
                    @error('replyAuthorName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label for="replyContent" class="block text-sm font-medium text-gray-700">Votre réponse</label>
                    <textarea id="replyContent" rows="3" wire:model="replyContent" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4"></textarea>
                    @error('replyContent') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm">
                        Publier la réponse
                    </button>
                </div>
            </form>
        @endif
    </div>
    
    <!-- Réponses au commentaire -->
    @if(count($comment->replies) > 0)
        <div class="mt-4 pl-6 border-l-2 border-gray-100 space-y-4">
            @foreach($comment->replies as $reply)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex justify-between">
                        <div class="flex items-center mb-3">
                            <div class="bg-blue-100 rounded-full p-1 mr-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 text-sm">{{ $reply->author_name }}</h4>
                                <p class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 text-xs">
                            <button class="flex items-center text-gray-500 hover:text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                </svg>
                                <span class="ml-1">{{ $reply->votes_up }}</span>
                            </button>
                            
                            <button class="flex items-center text-gray-500 hover:text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z" />
                                </svg>
                                <span class="ml-1">{{ $reply->votes_down }}</span>
                            </button>
                            
                            {{-- Bouton de suppression pour les réponses - visible uniquement pour les administrateurs --}}
                            @if($isAdmin)
                            <button 
                                onclick="confirmDelete({{ $reply->id }}, 'reply')" 
                                class="ml-3 flex items-center text-gray-500 hover:text-red-600" 
                                title="Supprimer cette réponse"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="prose prose-sm prose-blue max-w-none">
                        <p>{{ $reply->content }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Script pour la confirmation SweetAlert -->
    <script>
        function confirmDeleteComment(commentId, replyId) {
            const itemType = replyId ? 'réponse' : 'commentaire';
            
            Swal.fire({
                title: `Supprimer ce ${itemType} ?`,
                text: `Êtes-vous sûr de vouloir supprimer ce ${itemType} ? Cette action est irréversible.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (replyId) {
                        // Supprimer une réponse
                        Livewire.find('{{ $_instance->getId() }}').deleteComment(replyId);
                    } else {
                        // Supprimer un commentaire principal
                        Livewire.find('{{ $_instance->getId() }}').deleteComment();
                    }
                }
            });
        }
    </script>
</div>
