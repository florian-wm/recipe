<form action="{{ $action }}" method="POST" class="flex gap-4 items-end">
    @csrf
    @if(isset($method) && $method === 'PUT')
        @method('PUT')
    @endif
    <div>
        <label class="block text-sm font-medium">Nom</label>
        <input type="text" name="name" required value="{{ old('name', $category->name ?? '') }}" class="px-3 py-2 border rounded">
    </div>
    <div>
        <label class="block text-sm font-medium">Couleur</label>
        <input type="color" name="color" value="{{ old('color', $category->color ?? '#FFA500') }}" class="w-12 h-10 p-0 border-none">
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">{{ $submitLabel ?? (isset($category) && $category ? 'Mettre à jour' : 'Ajouter') }}</button>
</form> 