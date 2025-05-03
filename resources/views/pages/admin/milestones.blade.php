@extends('layouts.app')

@section('title', 'Gestion des jalons')
@section('header', 'Administration des jalons')

@section('content')
    <div class="bg-white shadow-sm rounded-lg p-6">
        <p class="mb-6 text-gray-600">
            Cette page vous permet de gérer les jalons du Travail de Fin d'Études. Vous pouvez ajouter, modifier ou supprimer des jalons,
            ainsi que changer leur ordre d'apparition dans la timeline.
        </p>
        
        @livewire('admin.milestones-manager')
    </div>
@endsection