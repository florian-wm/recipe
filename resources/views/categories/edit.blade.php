@extends('layouts.app')
@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold mb-6">Éditer la catégorie</h1>
    @php
        $action = route('categories.update', $category->id);
        $method = 'PUT';
        $submitLabel = 'Mettre à jour';
    @endphp
    @include('categories.form', compact('category', 'action', 'method', 'submitLabel'))
    <a href="{{ route('categories.index') }}" class="text-gray-600 underline">Retour à la liste</a>
</div>
@endsection 