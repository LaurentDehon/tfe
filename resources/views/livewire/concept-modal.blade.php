<div x-data>
    <div x-init="
        window.addEventListener('showConcept', (event) => {
            $wire.showConcept(event.detail.concept);
        });
    "></div>

    @if($showModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg overflow-hidden shadow-xl transform sm:max-w-2xl sm:w-full" @click.away="$wire.closeModal()">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <div class="flex items-center">
                            <h3 class="text-2xl font-semibold text-emerald-700">{{ $concept->name }}</h3>
                            @auth
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.concepts') }}?edit={{ $concept->id }}" class="ml-3 text-emerald-600 hover:text-emerald-800 transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                </a>
                            @endif
                            @endauth
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="py-4">
                        <div class="prose max-w-none">
                            @if($concept->description)
                                <p class="text-gray-700">{{ $concept->description }}</p>
                            @else
                                <p class="text-gray-400 italic">Aucune description disponible</p>
                            @endif
                        </div>
                        
                        @if($concept->urls && count($concept->urls) > 0)
                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-800 mb-3">Ressources</h4>
                                <ul class="space-y-2 ml-1">
                                    @foreach($concept->urls as $url)
                                        <li>
                                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" 
                                               class="text-blue-600 hover:text-blue-800 flex items-center group">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 015.656 0l4 4a4 4 0 01-5.656 5.656l-1.102-1.101" />
                                                </svg>
                                                <span>{{ parse_url($url, PHP_URL_HOST) ?? $url }}</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-3 flex justify-end">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>