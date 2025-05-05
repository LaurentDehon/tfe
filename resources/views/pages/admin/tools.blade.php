@extends('layouts.app')

@section('title', 'Gestion des outils')
@section('header', 'Administration des outils')

@section('content')
<div class="container mx-auto px-4 py-8">
    <livewire:admin.tools-manager />
</div>
@endsection