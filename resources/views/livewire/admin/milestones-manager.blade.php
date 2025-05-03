<div>
    <div class="mb-6 flex justify-between items-center">
        <button wire:click="openModal" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
            Ajouter un nouveau jalon
        </button>
        
        <div class="text-sm text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2h.01a 1 1 0 000-2H9z" clip-rule="evenodd" />
            </svg>
            Utilisez les boutons pour réorganiser les jalons
        </div>
    </div>
    
    <!-- Liste des jalons avec boutons de réorganisation -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($milestones as $milestone)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex flex-col mr-3">
                                    <!-- Bouton monter -->
                                    <button wire:click="moveUp({{ $milestone->id }})" class="text-gray-500 hover:text-blue-600 focus:outline-none" title="Monter">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                        </svg>
                                    </button>
                                    <!-- Bouton descendre -->
                                    <button wire:click="moveDown({{ $milestone->id }})" class="text-gray-500 hover:text-blue-600 focus:outline-none" title="Descendre">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="text-sm text-gray-900">{{ $milestone->position }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $milestone->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-500">{{ Str::limit($milestone->description, 100) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($milestone->documents && $milestone->documents->count() > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a 1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $milestone->documents->count() }} document(s)
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    Aucun document
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="openModal({{ $milestone->id }})" class="text-blue-600 hover:text-blue-900 mr-3">Modifier</button>
                            <button wire:click="deleteMilestone({{ $milestone->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce jalon ?')">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Aucun jalon n'a encore été défini. Cliquez sur "Ajouter un nouveau jalon" pour commencer.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Modal pour ajouter/modifier un jalon -->
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center border-b pb-3 mb-5">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $isEditing ? 'Modifier le jalon' : 'Ajouter un nouveau jalon' }}
                        </h3>
                        <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200 rounded-full p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="saveMilestone">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
                                <input type="text" id="title" wire:model="milestone.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4">
                                @error('milestone.title') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-span-6 sm:col-span-3">
                                <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                                <input type="number" id="position" wire:model="milestone.position" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4">
                                @error('milestone.position') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-span-6">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="description" rows="4" wire:model="milestone.description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4"></textarea>
                                @error('milestone.description') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-span-6">
                                <label for="tools" class="block text-sm font-medium text-gray-700 mb-1">Outils (séparés par des virgules)</label>
                                <input type="text" id="tools" wire:model="milestone.tools" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4">
                                @error('milestone.tools') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-span-6">
                                <label for="concepts" class="block text-sm font-medium text-gray-700 mb-1">Concepts (séparés par des virgules)</label>
                                <input type="text" id="concepts" wire:model="milestone.concepts" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4">
                                @error('milestone.concepts') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-span-6">
                                <label for="courses" class="block text-sm font-medium text-gray-700 mb-1">Cours associés (séparés par des virgules)</label>
                                <input type="text" id="courses" wire:model="milestone.courses" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-4">
                                @error('milestone.courses') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="col-span-6 mt-2">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Documents du jalon</label>
                                
                                <!-- Formulaire d'ajout de document -->
                                <div class="bg-gray-50 p-5 rounded-md mb-5 border border-gray-200 shadow-sm">
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Ajouter un document</h4>
                                    
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label for="documentToUpload" class="block text-xs font-medium text-gray-500 mb-1">Fichier</label>
                                            <input type="file" id="documentToUpload" wire:model="documentToUpload" class="mt-1 block w-full py-1.5 px-3 border border-gray-300 rounded-md text-sm">
                                            @error('documentToUpload') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="documentName" class="block text-xs font-medium text-gray-500 mb-1">Nom du document</label>
                                            <input type="text" id="documentName" wire:model="documentName" placeholder="Nom du document" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3">
                                            @error('documentName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="documentDescription" class="block text-xs font-medium text-gray-500 mb-1">Description (optionnelle)</label>
                                            <textarea id="documentDescription" wire:model="documentDescription" placeholder="Description du document" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3"></textarea>
                                            @error('documentDescription') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <button type="button" wire:click="uploadDocument" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            Ajouter le document
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Liste des documents existants -->
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-3">Documents enregistrés</h4>
                                    
                                    @if(count($milestoneDocuments) > 0)
                                        <div class="overflow-hidden bg-white shadow-sm rounded-md border border-gray-200">
                                            <ul role="list" class="divide-y divide-gray-200">
                                                @foreach($milestoneDocuments as $document)
                                                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center min-w-0">
                                                                <div class="flex-shrink-0">
                                                                    @php
                                                                        $fileType = $document->file_type;
                                                                        $iconColor = match(strtolower($fileType)) {
                                                                            'pdf' => 'text-red-500',
                                                                            'doc', 'docx' => 'text-blue-500',
                                                                            'xls', 'xlsx' => 'text-green-500',
                                                                            'ppt', 'pptx' => 'text-orange-500',
                                                                            default => 'text-gray-500'
                                                                        };
                                                                    @endphp
                                                                    <svg class="h-8 w-8 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                                                        <path d="M6 2a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6H6zm0 2h7v5h5v11H6V4zm2 8v2h8v-2H8zm0 4v2h5v-2H8z"/>
                                                                    </svg>
                                                                </div>
                                                                <div class="min-w-0 ml-4">
                                                                    <p class="truncate text-sm font-medium text-gray-900">{{ $document->name }}</p>
                                                                    @if($document->description)
                                                                        <p class="truncate text-xs text-gray-500">{{ $document->description }}</p>
                                                                    @endif
                                                                    <p class="mt-1 flex items-center text-xs text-gray-500">
                                                                        <span>{{ strtoupper($document->file_type) }} • {{ number_format($document->file_size / 1024, 0) }} KB</span>
                                                                        <span class="mx-1">•</span>
                                                                        <span>Ajouté le {{ $document->created_at->format('d/m/Y') }}</span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="ml-4 flex-shrink-0 flex space-x-3">
                                                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="rounded-md bg-white font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-xs px-3 py-2 border border-gray-300 shadow-sm">
                                                                    <div class="flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                        </svg>
                                                                        Voir
                                                                    </div>
                                                                </a>
                                                                <a href="{{ asset('storage/' . $document->file_path) }}" download class="rounded-md bg-white font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-xs px-3 py-2 border border-gray-300 shadow-sm">
                                                                    <div class="flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4V4" />
                                                                        </svg>
                                                                        Télécharger
                                                                    </div>
                                                                </a>
                                                                <button type="button" wire:click="deleteDocument({{ $document->id }})" class="rounded-md bg-white font-medium text-red-600 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-xs px-3 py-2 border border-gray-300 shadow-sm">
                                                                    <div class="flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                        Supprimer
                                                                    </div>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <div class="text-center py-6 text-gray-500 bg-gray-50 rounded-md border border-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun document</h3>
                                            <p class="mt-1 text-sm text-gray-500">Commencez par ajouter un document à ce jalon.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end space-x-4">
                            <button type="button" wire:click="closeModal" class="bg-white py-2.5 px-5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Annuler
                            </button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2.5 px-5 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ $isEditing ? 'Mettre à jour' : 'Enregistrer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<style>
    /* Styles pour les boutons de navigation */
    .hover\:bg-gray-50:hover {
        background-color: #f9fafb;
    }
    
    /* Animation subtile pour le feedback lors du clic */
    button {
        transition: transform 0.1s ease-in-out;
    }
    
    button:active {
        transform: scale(0.95);
    }
</style>
@endpush
