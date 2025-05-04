<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersManager extends Component
{
    use WithPagination;
    
    protected $paginationTheme = 'tailwind';
    
    public $showModal = false;
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $isEditing = false;
    public $isAdmin = false;
    
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    protected $listeners = ['refresh' => '$refresh'];
    
    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10]
    ];
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'isAdmin' => 'boolean',
    ];

    protected $validationAttributes = [
        'name' => 'nom',
        'email' => 'adresse email',
        'password' => 'mot de passe',
        'isAdmin' => 'administrateur'
    ];
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedPerPage()
    {
        $this->resetPage();
    }
    
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    
    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }
    
    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['userId', 'name', 'email', 'password', 'password_confirmation', 'isAdmin', 'isEditing']);
        $this->showModal = true;
    }
    
    public function closeModal()
    {
        $this->showModal = false;
    }
    
    public function edit(User $user)
    {
        $this->resetValidation();
        
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->isAdmin = $user->is_admin ? true : false;
        $this->isEditing = true;
        
        // Mettre à jour les règles de validation pour ignorer l'utilisateur en cours d'édition
        $this->rules['email'] = 'required|email|unique:users,email,' . $this->userId;
        $this->rules['password'] = 'nullable|min:8|confirmed'; // Le mot de passe est optionnel lors de l'édition
        
        $this->showModal = true;
    }
    
    public function save()
    {
        // Valider les données
        $validatedData = $this->validate();
        
        if ($this->isEditing) {
            $user = User::find($this->userId);
            
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'is_admin' => $this->isAdmin ? 1 : 0,
            ];
            
            // Mettre à jour le mot de passe seulement s'il est fourni
            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }
            
            $user->update($userData);
            
            $this->dispatch('notify', ['message' => 'Utilisateur mis à jour avec succès!']);
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_admin' => $this->isAdmin ? 1 : 0,
            ]);
            
            $this->dispatch('notify', ['message' => 'Utilisateur ajouté avec succès!']);
        }
        
        $this->closeModal();
    }
    
    public function delete(User $user)
    {
        // Ne pas supprimer l'utilisateur actuellement connecté
        if ($user->id === Auth::id()) {
            $this->dispatch('notify', ['message' => 'Vous ne pouvez pas supprimer votre propre compte!', 'type' => 'error']);
            return;
        }
        
        $user->delete();
        $this->dispatch('notify', ['message' => 'Utilisateur supprimé avec succès!']);
    }
    
    public function render()
    {
        return view('livewire.admin.users-manager', [
            'users' => User::when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('email', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
