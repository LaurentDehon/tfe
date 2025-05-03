@extends('layouts.app')

@section('title', 'Commentaires')
@section('header', 'Espace de discussion')

@section('content')
    <div class="bg-white shadow-sm rounded-lg p-6">
        <p class="mb-6 text-gray-600">
            Cette page vous permet de discuter et partager vos idées concernant le TFE. Vous pouvez ajouter de nouveaux commentaires, 
            répondre aux commentaires existants et voter pour les commentaires les plus pertinents.
        </p>
        
        @livewire('comments-list')
    </div>
@endsection