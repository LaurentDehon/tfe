<div class="max-w-2xl mx-auto py-8 px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Mon profil</h1>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Informations personnelles</h2>
        <form wire:submit="updateProfile">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" wire:model="name" id="name" required
                    class="mt-1 block w-full py-2 px-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email" id="email" required
                    class="mt-1 block w-full py-2 px-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Mettre à jour les informations
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Changer de mot de passe</h2>
        <form wire:submit="updatePassword">
            <div class="mb-4">
                <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                <input type="password" wire:model="current_password" id="current_password" required
                    class="mt-1 block w-full py-2 px-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('current_password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                <input type="password" wire:model="password" id="password" required
                    class="mt-1 block w-full py-2 px-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le nouveau mot de passe</label>
                <input type="password" wire:model="password_confirmation" id="password_confirmation" required
                    class="mt-1 block w-full py-2 px-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Changer le mot de passe
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4 text-red-600">Supprimer mon compte</h2>
        <p class="mb-4 text-gray-600">Une fois que votre compte est supprimé, toutes vos ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger les données que vous souhaitez conserver.</p>
        
        @if(auth()->user()->is_admin)
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
                <p>Les comptes administrateurs ne peuvent pas être supprimés pour des raisons de sécurité.</p>
            </div>
        @else
            <form wire:submit="destroyAccount">
                <div class="mb-4">
                    <label for="delete_password" class="block text-sm font-medium text-gray-700">Veuillez saisir votre mot de passe pour confirmer</label>
                    <input type="password" wire:model="delete_password" id="delete_password" required
                        class="mt-1 block w-full py-2 px-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('delete_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.')">
                        Supprimer mon compte
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
