@extends('layouts.app')

@section('title', 'Gestion des concepts')
@section('header', 'Administration des concepts')

@section('content')
<div class="container mx-auto px-4 py-8">
    <livewire:admin.concepts-manager />
</div>
@endsection