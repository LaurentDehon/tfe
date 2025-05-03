<div class="max-w-3xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Gestion des jalons (Milestones)</h2>
    <div class="mb-6 flex justify-end">
        <button wire:click="showCreateForm" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Ajouter un jalon</button>
    </div>
    @if($showForm)
        <div class="bg-white p-6 rounded shadow mb-8">
            <h3 class="text-lg font-semibold mb-4">{{ $isEdit ? 'Modifier' : 'Ajouter' }} un jalon</h3>
            <form wire:submit.prevent="saveMilestone" class="space-y-4">
                <div>
                    <label class="block font-medium">Titre</label>
                    <input type="text" wire:model.defer="title" class="w-full border rounded px-3 py-2 mt-1" required>
                </div>
                <div>
                    <label class="block font-medium">Description</label>
                    <textarea wire:model.defer="description" class="w-full border rounded px-3 py-2 mt-1" rows="3" required></textarea>
                </div>
                <div>
                    <label class="block font-medium">Modèle de document (chemin ou URL)</label>
                    <input type="text" wire:model.defer="document_template_path" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="block font-medium">Outils (séparés par des virgules)</label>
                    <input type="text" wire:model.defer="tools" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="block font-medium">Concepts (séparés par des virgules)</label>
                    <input type="text" wire:model.defer="concepts" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div>
                    <label class="block font-medium">Cours associés (séparés par des virgules)</label>
                    <input type="text" wire:model.defer="courses" class="w-full border rounded px-3 py-2 mt-1">
                </div>
                <div class="flex space-x-2 mt-4">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">{{ $isEdit ? 'Enregistrer' : 'Créer' }}</button>
                    <button type="button" wire:click="cancelForm" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">Annuler</button>
                </div>
            </form>
        </div>
    @endif
    <div class="bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($milestones as $milestone)
                    <tr>
                        <td class="px-4 py-2 font-semibold">{{ $milestone->title }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ Str::limit($milestone->description, 60) }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <button wire:click="showEditForm({{ $milestone->id }})" class="text-blue-600 hover:underline">Modifier</button>
                            <button wire:click="deleteMilestone({{ $milestone->id }})" class="text-red-600 hover:underline" onclick="return confirm('Supprimer ce jalon ?')">Supprimer</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-400">Aucun jalon.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>