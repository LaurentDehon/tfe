<div>
    <div class="max-w-2xl mx-auto py-8">
        <h2 class="text-3xl font-bold mb-8 text-center">Ligne du temps du TFE</h2>
        <div class="relative border-l-2 border-blue-200">
            @forelse($milestones as $milestone)
                <div class="mb-10 ml-6 group">
                    <span class="absolute w-4 h-4 bg-blue-500 rounded-full -left-2 border-4 border-white"></span>
                    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition cursor-pointer" wire:click="$emit('openMilestoneModal', {{ $milestone->id }})">
                        <h3 class="text-xl font-semibold text-blue-700 group-hover:underline">{{ $milestone->title }}</h3>
                        <p class="text-gray-600 mt-2 line-clamp-2">{{ $milestone->description }}</p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @if($milestone->tools)
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">Outils</span>
                            @endif
                            @if($milestone->concepts)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Concepts</span>
                            @endif
                            @if($milestone->courses)
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">Cours</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500">Aucun jalon pour le moment.</p>
            @endforelse
        </div>

        @if($showModal && $selectedMilestone)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative animate-fade-in">
                    <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                    <h3 class="text-2xl font-bold mb-4 text-blue-700">{{ $selectedMilestone->title }}</h3>
                    <div class="mb-4 border-b flex space-x-4">
                        <button wire:click="setTab('details')" class="py-2 px-4 focus:outline-none border-b-2 transition {{ $activeTab === 'details' ? 'border-blue-500 text-blue-700 font-semibold' : 'border-transparent text-gray-500' }}">Détails</button>
                        <button wire:click="setTab('document')" class="py-2 px-4 focus:outline-none border-b-2 transition {{ $activeTab === 'document' ? 'border-blue-500 text-blue-700 font-semibold' : 'border-transparent text-gray-500' }}">Modèle de document</button>
                        <button wire:click="setTab('tools')" class="py-2 px-4 focus:outline-none border-b-2 transition {{ $activeTab === 'tools' ? 'border-blue-500 text-blue-700 font-semibold' : 'border-transparent text-gray-500' }}">Outils</button>
                        <button wire:click="setTab('concepts')" class="py-2 px-4 focus:outline-none border-b-2 transition {{ $activeTab === 'concepts' ? 'border-blue-500 text-blue-700 font-semibold' : 'border-transparent text-gray-500' }}">Concepts</button>
                        <button wire:click="setTab('courses')" class="py-2 px-4 focus:outline-none border-b-2 transition {{ $activeTab === 'courses' ? 'border-blue-500 text-blue-700 font-semibold' : 'border-transparent text-gray-500' }}">Cours</button>
                    </div>
                    <div class="mt-4 min-h-[120px]">
                        @if($activeTab === 'details')
                            <div>
                                <p class="text-gray-700 whitespace-pre-line">{{ $selectedMilestone->description }}</p>
                            </div>
                        @elseif($activeTab === 'document')
                            @if($selectedMilestone->document_template_path)
                                <a href="{{ asset('storage/' . $selectedMilestone->document_template_path) }}" class="text-blue-600 underline" download>Télécharger le modèle</a>
                            @else
                                <p class="text-gray-500">Aucun modèle disponible.</p>
                            @endif
                        @elseif($activeTab === 'tools')
                            <ul class="list-disc ml-5 text-gray-700">
                                @foreach((array) json_decode($selectedMilestone->tools) as $tool)
                                    <li>{{ $tool }}</li>
                                @endforeach
                            </ul>
                        @elseif($activeTab === 'concepts')
                            <ul class="list-disc ml-5 text-gray-700">
                                @foreach((array) json_decode($selectedMilestone->concepts) as $concept)
                                    <li>{{ $concept }}</li>
                                @endforeach
                            </ul>
                        @elseif($activeTab === 'courses')
                            <ul class="list-disc ml-5 text-gray-700">
                                @foreach((array) json_decode($selectedMilestone->courses) as $course)
                                    <li>{{ $course }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>