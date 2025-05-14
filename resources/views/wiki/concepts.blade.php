@extends('layouts.app')

@section('title', 'Wiki des Concepts')

@section('content')
<div class="bg-gray-50 min-h-screen py-8" x-data="{}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <header class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Wiki des Concepts</h1>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                Explorez notre base de connaissances des concepts clés avec leurs descriptions et ressources associées.
            </p>
        </header>

        <!-- Index alphabétique -->
        <div class="mb-8 bg-white rounded-xl shadow-sm p-4 sticky top-0 z-10">
            <nav class="flex flex-wrap justify-center gap-2 md:gap-3" aria-label="Index alphabétique">
                @foreach($groupedConcepts as $letter => $concepts)
                    <a href="#letter-{{ $letter }}" class="w-8 h-8 flex items-center justify-center rounded-md bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-medium transition-colors">
                        {{ $letter }}
                    </a>
                @endforeach
            </nav>
        </div>
        
        <!-- Contenu principal -->
        <div class="space-y-10 mb-10">
            @foreach($groupedConcepts as $letter => $concepts)
                <section id="letter-{{ $letter }}" class="scroll-mt-20">
                    <div class="flex items-center mb-4">
                        <h2 class="text-2xl font-bold text-emerald-700">{{ $letter }}</h2>
                        <div class="h-px flex-grow bg-emerald-200 ml-4"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($concepts as $concept)
                            <article 
                                class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6 border-l-4 border-emerald-500 cursor-pointer h-60 flex flex-col"
                                x-on:click="window.dispatchEvent(new CustomEvent('showConcept', { detail: { concept: {{ $concept->id }} } }))">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $concept->name }}</h3>
                                
                                <div class="prose prose-sm text-gray-600 mb-4 overflow-hidden flex-grow">
                                    @if($concept->description)
                                        <div class="line-clamp-4">{{ $concept->description }}</div>
                                        @if(strlen($concept->description) > 200)
                                            <div class="mt-2 text-sm text-emerald-600 font-medium">Cliquez pour voir plus...</div>
                                        @endif
                                    @else
                                        <p class="text-gray-400 italic">Aucune description disponible</p>
                                    @endif
                                </div>
                                
                                @if($concept->urls && count($concept->urls) > 0)
                                    <div class="mt-auto pt-3 border-t border-gray-100">
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" />
                                            </svg>
                                            {{ count($concept->urls) }} ressource(s)
                                        </div>
                                    </div>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
        
        <!-- Bouton retour en haut -->
        <div class="text-center">
            <a href="#" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-base font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                Retour en haut
            </a>
        </div>
    </div>
</div>

<!-- Inclure le composant modal pour les concepts -->
<livewire:concept-modal />
@endsection

@push('styles')
<style>
    /* Animation de défilement fluide pour les ancres */
    html {
        scroll-behavior: smooth;
    }
    
    /* Classes pour limiter le texte à un nombre de lignes */
    .line-clamp-4 {
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 4;
        overflow: hidden;
    }
</style>
@endpush