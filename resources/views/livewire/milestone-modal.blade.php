<div>
    @if($showModal && $milestone)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white">
                        <!-- Modal header with close button -->
                        <div class="flex justify-between items-center p-4 border-b">
                            <h3 class="text-lg font-medium text-gray-900">{{ $milestone->title }}</h3>
                            <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Tabs -->
                        <div class="border-b border-gray-200">
                            <nav class="flex -mb-px" aria-label="Tabs">
                                <button
                                    wire:click="setActiveTab('details')"
                                    class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $activeTab === 'details' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                >
                                    Détails
                                </button>
                                @if(count($milestone->documents) > 0)
                                <button
                                    wire:click="setActiveTab('documents')"
                                    class="py-4 px-6 text-center border-b-2 font-medium text-sm {{ $activeTab === 'documents' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                                >
                                    Documents
                                    <span class="ml-1 px-2 py-0.5 text-xs bg-blue-100 text-blue-600 rounded-full">{{ count($milestone->documents) }}</span>
                                </button>
                                @endif
                            </nav>
                        </div>

                        <!-- Tab content -->
                        <div class="p-6">
                            <!-- Details tab -->
                            @if($activeTab === 'details')
                                <div>
                                    <!-- Description -->
                                    <div class="mb-6">
                                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Description</h4>
                                        <div class="prose prose-sm max-w-none text-gray-900">
                                            {{ $milestone->description }}
                                        </div>
                                    </div>
                                    
                                    <!-- Timing -->
                                    @if($milestone->timing_months)
                                    <div class="mb-6">
                                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Timing recommandé</h4>
                                        <div class="flex items-center text-purple-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            <span>{{ $milestone->timing_months }} mois avant la date de présentation finale</span>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <!-- Tools -->
                                    @if(count($milestone->toolsArray) > 0)
                                        <div class="mb-6">
                                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Outils</h4>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($milestone->toolsArray as $tool)
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-sky-100 text-sky-800 {{ isset($toolsWithUrls[$tool]) ? 'cursor-pointer hover:bg-blue-200' : '' }}">
                                                        @if(isset($toolsWithUrls[$tool]))
                                                            <a href="{{ $toolsWithUrls[$tool] }}" target="_blank" class="flex items-center">
                                                                {{ $tool }}
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                </svg>
                                                            </a>
                                                        @else
                                                            {{ $tool }}
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Concepts -->
                                    @if(count($milestone->conceptsArray) > 0)
                                        <div class="mb-6">
                                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Concepts</h4>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($milestone->conceptsArray as $concept)
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                                        {{ $concept }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Courses -->
                                    @if(count($milestone->coursesArray) > 0)
                                        <div class="mb-6">
                                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Cours associés</h4>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($milestone->coursesArray as $course)
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-violet-100 text-violet-800">
                                                        {{ $course }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Documents tab -->
                            @if($activeTab === 'documents')
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">Documents associés</h4>
                                    
                                    @if(count($milestone->documents) > 0)
                                        <div class="overflow-hidden bg-white shadow sm:rounded-md">
                                            <ul role="list" class="divide-y divide-gray-200">
                                                @foreach($milestone->documents as $document)
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
                                                            <div class="ml-4 flex-shrink-0 flex space-x-2">
                                                                <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank" class="rounded-md bg-white font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm px-2 py-1 border border-gray-300">
                                                                    <div class="flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                        </svg>
                                                                        Voir
                                                                    </div>
                                                                </a>
                                                                <a href="{{ asset('storage/' . $document->file_path) }}" download class="rounded-md bg-white font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm px-2 py-1 border border-gray-300">
                                                                    <div class="flex items-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4V4" />
                                                                        </svg>
                                                                        Télécharger
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun document disponible</h3>
                                            <p class="mt-1 text-sm text-gray-500">Aucun document n'a été associé à ce jalon.</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
