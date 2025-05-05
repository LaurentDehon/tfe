<div id="concepts-manager" wire:id="{{ $_instance->getId() }}">
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900 pe-40">Gestion des concepts</h1>
        
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Rechercher..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    @if($search)
                        <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 transition-colors">✕</button>
                    @endif
                </div>
                
                <select wire:model.live="perPage" class="pl-3 pr-10 py-2 border border-gray-300 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full">
                    <option value="5">5 par page</option>
                    <option value="10">10 par page</option>
                    <option value="25">25 par page</option>
                    <option value="50">50 par page</option>
                </select>
            </div>
            
            <button 
                wire:click="openModal" 
                class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Nouveau concept
            </button>
        </div>
    </div>
    
    <!-- Liste des concepts -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center cursor-pointer" wire:click="sortBy('name')">
                            Nom
                            @if($sortField === 'name')
                                @if($sortDirection === 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            @endif
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center cursor-pointer" wire:click="sortBy('created_at')">
                            Date de création
                            @if($sortField === 'created_at')
                                @if($sortDirection === 'asc')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            @endif
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($concepts as $concept)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $concept->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $concept->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <button 
                                wire:click="edit({{ $concept->id }})" 
                                class="text-emerald-600 hover:text-emerald-900"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <span class="sr-only">Modifier</span>
                            </button>
                            <button 
                                onclick="confirmDelete({{ $concept->id }}, 'concepts-manager')" 
                                class="text-red-600 hover:text-red-900"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="sr-only">Supprimer</span>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                            Aucun concept trouvé. Cliquez sur "Nouveau concept" pour en ajouter un.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        @if($concepts->hasPages())
            <div class="flex items-center justify-between bg-white px-4 py-3 sm:px-6 rounded-lg shadow-sm">
                <div class="flex flex-1 items-center justify-between sm:hidden">
                    <div>
                        <p class="text-sm text-gray-700">
                            Page <span class="font-medium">{{ $concepts->currentPage() }}</span> sur <span class="font-medium">{{ $concepts->lastPage() }}</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="$set('page', 1)" class="{{ $concepts->onFirstPage() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }} relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-green-300 active:bg-gray-100 active:text-gray-700 transition" {{ $concepts->onFirstPage() ? 'disabled' : '' }}>
                            «
                        </button>
                        <button wire:click="previousPage" class="{{ $concepts->onFirstPage() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }} relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-green-300 active:bg-gray-100 active:text-gray-700 transition" {{ $concepts->onFirstPage() ? 'disabled' : '' }}>
                            ‹
                        </button>
                        <button wire:click="nextPage" class="{{ !$concepts->hasMorePages() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }} relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-green-300 active:bg-gray-100 active:text-gray-700 transition" {{ !$concepts->hasMorePages() ? 'disabled' : '' }}>
                            ›
                        </button>
                        <button wire:click="$set('page', {{ $concepts->lastPage() }})" class="{{ !$concepts->hasMorePages() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }} relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-green-300 active:bg-gray-100 active:text-gray-700 transition" {{ !$concepts->hasMorePages() ? 'disabled' : '' }}>
                            »
                        </button>
                    </div>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Affichage de <span class="font-medium">{{ $concepts->firstItem() ?: 0 }}</span> à <span class="font-medium">{{ $concepts->lastItem() ?: 0 }}</span> sur <span class="font-medium">{{ $concepts->total() }}</span> résultats
                        </p>
                    </div>
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <button wire:click=goToPage(1)" class="{{ $concepts->onFirstPage() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }} relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0" {{ $concepts->onFirstPage() ? 'disabled' : '' }}>
                                <span class="sr-only">Première page</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button wire:click="previousPage" class="{{ $concepts->onFirstPage() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }} relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0" {{ $concepts->onFirstPage() ? 'disabled' : '' }}>
                                <span class="sr-only">Page précédente</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">
                                Page {{ $concepts->currentPage() }} sur {{ $concepts->lastPage() }}
                            </span>
                            
                            <button wire:click="nextPage" class="{{ !$concepts->hasMorePages() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }} relative inline-flex items-center px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0" {{ !$concepts->hasMorePages() ? 'disabled' : '' }}>
                                <span class="sr-only">Page suivante</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button wire:click="goToPage({{ $concepts->lastPage() }})" class="{{ !$concepts->hasMorePages() ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer' }} relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0" {{ !$concepts->hasMorePages() ? 'disabled' : '' }}>
                                <span class="sr-only">Dernière page</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L8.586 10 4.293 14.293a1 1 0 000 1.414zm6 0a1 1 0 001.414 0l5-5a1 1 0 000-1.414l-5-5a1 1 0 00-1.414 1.414L15.586 10l-4.293 4.293a1 1 0 000 1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        @else
            <p class="text-sm text-gray-700">Affichage de tous les résultats</p>
        @endif
    </div>
    
    <!-- Modal pour ajouter/modifier un concept -->
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center border-b pb-3 mb-5">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $isEditing ? 'Modifier le concept' : 'Ajouter un nouveau concept' }}
                        </h3>
                        <button 
                            wire:click="closeModal" 
                            class="text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="save">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                            <input 
                                type="text" 
                                id="name" 
                                wire:model="name" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm py-2 px-3"
                            >
                            @error('name') 
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span> 
                            @enderror
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button 
                                type="button" 
                                wire:click="closeModal" 
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                Annuler
                            </button>
                            <button 
                                type="submit" 
                                class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                            >
                                {{ $isEditing ? 'Mettre à jour' : 'Enregistrer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
