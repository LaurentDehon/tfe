@extends('layouts.app')

@section('title', 'Gestion des cours')
@section('header', 'Administration des cours')

@section('content')
<div class="container mx-auto px-4 py-8">
    <livewire:admin.courses-manager />
</div>
@endsection