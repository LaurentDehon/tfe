<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class Profile extends Component
{
    public $name;
    public $email;
    public $current_password;
    public $password;
    public $password_confirmation;
    public $delete_password;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->name = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->dispatch('notify', type: 'success', message: 'Votre profil a été mis à jour avec succès.');
    }

    public function updatePassword()
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        $this->dispatch('notify', type: 'success', message: 'Mot de passe mis à jour avec succès.');
    }

    public function destroyAccount()
    {
        $this->validate([
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        Auth::logout();

        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('timeline')->with('success', 'Votre compte a été supprimé avec succès.');
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
