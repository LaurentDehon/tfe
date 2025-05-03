<div>
    <!-- Timeline verticale des jalons -->
    <div class="relative pb-12">
        <!-- Timeline avec les jalons -->
        <div class="flex flex-col space-y-8 ml-4">
            @forelse($milestones as $milestone)
                <div class="relative">
                    <!-- Ligne verticale -->
                    <div class="absolute top-0 -bottom-8 left-0 w-0.5 bg-gray-200"></div>
                    
                    <!-- Point du jalon -->
                    <div class="relative flex items-start">
                        <div class="h-8 w-8 rounded-full border-2 border-blue-500 bg-white flex items-center justify-center z-10 -ml-4">
                            <div class="h-4 w-4 rounded-full bg-blue-500"></div>
                        </div>
                        
                        <!-- Contenu du jalon -->
                        <div class="ml-6 cursor-pointer" wire:click="selectMilestone({{ $milestone->id }})">
                            <h4 class="text-lg font-semibold text-gray-900 hover:text-blue-600">
                                {{ $milestone->title }}
                            </h4>
                            <p class="text-gray-500 mt-1">
                                {{ Str::limit($milestone->description, 100) }}
                            </p>
                            <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Cliquer pour voir les détails
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-gray-500 py-10 text-center">
                    <p>Aucun jalon n'a encore été défini pour ce TFE.</p>
                    <p class="mt-2">Consultez l'espace d'administration pour ajouter des jalons.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal pour afficher les détails d'un jalon -->
    @if($showMilestoneModal && $selectedMilestone)
        <div class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $selectedMilestone->title }}
                        </h3>
                        <button wire:click="closeMilestoneModal" class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Onglets -->
                    <div x-data="{ activeTab: 'details' }">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8">
                                <button @click="activeTab = 'details'" 
                                    :class="{'border-blue-500 text-blue-600': activeTab === 'details', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'details'}"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Détails
                                </button>
                                <button @click="activeTab = 'document'" 
                                    :class="{'border-blue-500 text-blue-600': activeTab === 'document', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'document'}"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Modèle de document
                                </button>
                                <button @click="activeTab = 'tools'" 
                                    :class="{'border-blue-500 text-blue-600': activeTab === 'tools', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'tools'}"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Outils
                                </button>
                                <button @click="activeTab = 'concepts'" 
                                    :class="{'border-blue-500 text-blue-600': activeTab === 'concepts', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'concepts'}"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Concepts
                                </button>
                                <button @click="activeTab = 'courses'" 
                                    :class="{'border-blue-500 text-blue-600': activeTab === 'courses', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'courses'}"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Cours associés
                                </button>
                            </nav>
                        </div>
                        
                        <!-- Contenu des onglets -->
                        <div class="py-6">
                            <div x-show="activeTab === 'details'" class="space-y-4">
                                <h4 class="text-lg font-medium text-gray-900">Description</h4>
                                <div class="prose prose-blue max-w-none">
                                    {{ $selectedMilestone->description }}
                                </div>
                            </div>
                            
                            <div x-show="activeTab === 'document'" class="space-y-4">
                                <h4 class="text-lg font-medium text-gray-900">Modèle de document</h4>
                                @if($selectedMilestone->document_template_path)
                                    <div class="flex items-center space-x-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                        </svg>
                                        <a href="{{ Storage::url($selectedMilestone->document_template_path) }}" class="text-blue-600 hover:text-blue-800 underline" download>
                                            Télécharger le modèle de document
                                        </a>
                                    </div>
                                @else
                                    <p class="text-gray-500">Aucun modèle de document n'est disponible pour ce jalon.</p>
                                @endif
                            </div>
                            
                            <div x-show="activeTab === 'tools'" class="space-y-4">
                                <h4 class="text-lg font-medium text-gray-900">Outils à utiliser</h4>
                                @if(count($selectedMilestone->toolsArray) > 0)
                                    <ul class="list-disc pl-6 space-y-2">
                                        @foreach($selectedMilestone->toolsArray as $tool)
                                            <li class="text-gray-700">{{ trim($tool) }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">Aucun outil n'est spécifié pour ce jalon.</p>
                                @endif
                            </div>
                            
                            <div x-show="activeTab === 'concepts'" class="space-y-4">
                                <h4 class="text-lg font-medium text-gray-900">Concepts liés</h4>
                                @if(count($selectedMilestone->conceptsArray) > 0)
                                    <ul class="list-disc pl-6 space-y-2">
                                        @foreach($selectedMilestone->conceptsArray as $concept)
                                            <li class="text-gray-700">{{ trim($concept) }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">Aucun concept n'est spécifié pour ce jalon.</p>
                                @endif
                            </div>
                            
                            <div x-show="activeTab === 'courses'" class="space-y-4">
                                <h4 class="text-lg font-medium text-gray-900">Cours associés</h4>
                                @if(count($selectedMilestone->coursesArray) > 0)
                                    <ul class="list-disc pl-6 space-y-2">
                                        @foreach($selectedMilestone->coursesArray as $course)
                                            <li class="text-gray-700">{{ trim($course) }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-gray-500">Aucun cours n'est associé à ce jalon.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
