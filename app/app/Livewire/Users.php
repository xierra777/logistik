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

        if ($this->profile_photo) {
            $photoPath = $this->profile_photo->store('profile_photos', 'public');
        } else {
            $photoPath = null;
        }
        User::create([
            'name'          => $this->name,
            'email'         => $this->email,
            'password'      => Hash::make($this->password),
            'role'          => $this->role,
            'profile_photo' => $photoPath,
        ]);

        // dd($photoPath);

        // Refresh data users in table
        session()->flash('message', 'User created successfully.');
        $this->resetForm();
        $this->dispatch('close-modal');
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

    public function confirmDelete($get_id)
    {
        try {
            User::destroy($get_id);
            session()->flash('message', 'Shipment deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting shipment: ' . $e->getMessage());
        }
    }
}
