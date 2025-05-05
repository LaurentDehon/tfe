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
    public $page = 1;
    
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    
    protected $listeners = ['refresh' => '$refresh', 'users-manager:delete' => 'delete'];
    
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
        
        $this->showModal = true;
    }
    
    public function save()
    {
        if ($this->isEditing) {
            $this->rules['email'] = 'required|email|unique:users,email,' . $this->userId;
            $this->rules['password'] = 'nullable|min:8|confirmed';
            
            if (empty($this->password)) {
                unset($this->rules['password']);
            }
        } else {
            $this->rules['email'] = 'required|email|unique:users,email';
            $this->rules['password'] = 'required|min:8|confirmed';
        }
        
        // Valider les données
        $validatedData = $this->validate();
        
        if ($this->isEditing) {
            $user = User::find($this->userId);
            
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'is_admin' => $this->isAdmin ? 1 : 0,
            ];
            
            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }
            
            $user->update($userData);
            
            $this->dispatch('notify', type: 'success', title: 'Mise à jour réussie', message: 'L\'utilisateur a été mis à jour avec succès!');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_admin' => $this->isAdmin ? 1 : 0,
            ]);
            
            $this->dispatch('notify', type: 'success', title: 'Ajout réussi', message: 'Nouvel utilisateur ajouté avec succès!');
        }
        
        $this->closeModal();
    }
    
    public function delete($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            $this->dispatch('notify', type: 'error', title: 'Action impossible', message: 'Vous ne pouvez pas supprimer votre propre compte!');
            return;
        }
        
        $user->delete();
        
        $this->dispatch('notify', type: 'success', title: 'Suppression réussie', message: 'L\'utilisateur a été supprimé avec succès!');
    }
    
    public function goToPage($pageNumber)
    {
        $this->setPage($pageNumber);
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
