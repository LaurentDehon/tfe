<div class="py-4">
    <!-- Timeline verticale des jalons avec un design amélioré -->
    <div class="relative pb-12">
        <!-- Timeline avec les jalons -->
        <div class="flex flex-col space-y-12 ml-6">
            @forelse($milestones as $index => $milestone)
                <div class="relative">
                    <!-- Ligne verticale avec dégradé de couleur -->
                    @if(!$loop->last)
                        <!-- Ligne complète pour tous les jalons sauf le dernier -->
                        <div class="absolute top-0 -bottom-12 -left-0.5 w-1 bg-gradient-to-b from-blue-500 to-indigo-600"></div>
                    @else
                        <!-- Pour le dernier jalon, la ligne s'arrête exactement à la hauteur du cercle -->
                        <div class="absolute top-0 h-5 -left-0.5 w-1 bg-gradient-to-b from-blue-500 to-indigo-600"></div>
                    @endif
                    
                    <!-- Point du jalon avec animation au survol -->
                    <div class="relative flex items-start group">
                        <div class="h-10 w-10 rounded-full border-4 border-blue-500 bg-white flex items-center justify-center z-10 -ml-5 transition-all duration-300 ease-in-out transform group-hover:scale-110 group-hover:border-indigo-600 shadow-md">
                            <div class="h-4 w-4 rounded-full bg-blue-500 group-hover:bg-indigo-600 transition-colors duration-300"></div>
                        </div>
                        
                        <!-- Contenu du jalon avec carte améliorée -->
                        <div class="ml-8 bg-white rounded-lg p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 w-full max-w-2xl cursor-pointer transform hover:-translate-y-1"
                             wire:click="selectMilestone({{ $milestone->id }})">
                            <h4 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300">
                                {{ $milestone->title }}
                            </h4>
                            
                            <p class="text-gray-600 mt-2 leading-relaxed">
                                {{ Str::limit($milestone->description, 150) }}
                            </p>
                            
                            <!-- Indicateurs pour les documents et outils avec design amélioré -->
                            <div class="flex flex-wrap gap-2 mt-4">
                                @if($milestone->timing_months)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        <span>{{ $milestone->timing_months }} mois avant présentation</span>
                                    </span>
                                @endif

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                                    </svg>
                                    <span>Voir les détails</span>
                                </span>
                                
                                @if($milestone->documents && $milestone->documents->count() > 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200 shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                        </svg>
                                        <span>{{ $milestone->documents->count() }} document(s)</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 rounded-lg py-10 px-6 text-center border border-gray-200 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-lg font-medium text-gray-600">Aucun jalon n'a encore été défini pour ce TFE.</p>
                    <p class="mt-2 text-gray-500">Consultez l'espace d'administration pour ajouter des jalons.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- On utilise le composant MilestoneModal au lieu de la modal intégrée -->
    <livewire:milestone-modal />
</div>
