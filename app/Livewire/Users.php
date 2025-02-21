<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    use WithFileUploads, WithPagination;

    public $name, $email, $password, $profile_photo, $role;
    public $perPage = 5;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        // Handle profile photo upload if exists
        if ($this->profile_photo) {
            $path = $this->profile_photo->store('profile_photos', 'public');
            // Save $path to the database, etc.
        }
        // Create the new user
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'profile_photo' => $this->profile_photo,
        ]);

        // Refresh data users in table
        session()->flash('message', 'User created successfully.');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->profile_photo = null;
    }

    public function render()
    {
        // Fetch paginated users within the render method
        $users = User::latest()->paginate($this->perPage);

        return view('livewire.users', [
            'users' => $users
        ]);
    }
}
