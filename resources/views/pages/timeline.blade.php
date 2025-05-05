@extends('layouts.app')

@section('title', 'Timeline du TFE')
@section('header', 'Timeline du TFE')

@section('content')
    <div class="bg-white shadow-sm rounded-lg p-6">
        <p class="mb-6 text-gray-600">
            Cette page présente les différents jalons du Travail de Fin d'Études sous forme de timeline. 
            Cliquez sur un jalon pour afficher ses détails et les ressources associées.
        </p>
        
        @livewire('timeline')
    </div>
@endsection