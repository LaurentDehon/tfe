@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')
@section('header', 'Administration des utilisateurs')

@section('content')
<div class="container mx-auto px-4 py-8">
    <livewire:admin.users-manager />
</div>
@endsection