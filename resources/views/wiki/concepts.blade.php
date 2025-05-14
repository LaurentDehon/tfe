@extends('layouts.app')

@section('title', 'Wiki des Concepts')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
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
                            <article class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6 border-l-4 border-emerald-500">
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $concept->name }}</h3>
                                
                                <div class="prose prose-sm text-gray-600 mb-4">
                                    @if($concept->description)
                                        <p>{{ $concept->description }}</p>
                                    @else
                                        <p class="text-gray-400 italic">Aucune description disponible</p>
                                    @endif
                                </div>
                                
                                @if($concept->urls && count($concept->urls) > 0)
                                    <div class="mt-4">
                                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Ressources:</h4>
                                        <ul class="space-y-1">
                                            @foreach($concept->urls as $url)
                                                <li>
                                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" 
                                                       class="text-blue-600 hover:text-blue-800 text-sm flex items-center group">
                                                        <span class="truncate">{{ parse_url($url, PHP_URL_HOST) }}</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                        </svg>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
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
@endsection

@push('styles')
<style>
    /* Animation de défilement fluide pour les ancres */
    html {
        scroll-behavior: smooth;
    }
</style>
@endpush