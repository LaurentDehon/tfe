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
                            
                            <!-- Indicateurs pour les documents et outils -->
                            <div class="flex flex-wrap gap-1 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                    </svg>
                                    Cliquer pour voir les détails
                                </span>
                                
                                @if($milestone->documents && $milestone->documents->count() > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $milestone->documents->count() }} document(s)
                                    </span>
                                @endif
                            </div>
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

    <!-- On utilise le composant MilestoneModal au lieu de la modal intégrée -->
    <livewire:milestone-modal />
</div>
