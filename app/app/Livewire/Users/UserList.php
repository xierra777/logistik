<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserList extends Component
{
    use WithFileUploads, WithPagination;

    public $name, $email, $password, $profile_photo, $role;
    public $perPage = 5;
    public $showEditModal = false;
    public $selectedUserId;
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

    public function opencase($id)
    {
        $this->showEditModal = true;
        $user = User::findOrFail($id);
        $this->selectedUserId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; // kosongkan password saat edit
        $this->showEditModal = true;
    }
    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->selectedUserId,
            'role' => 'required',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = User::findOrFail($this->selectedUserId);

        if ($this->profile_photo) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $this->profile_photo->store('profile_photos', 'public');
        }

        $user->name = $this->name;
        $user->email = $this->email;
        $user->role = $this->role;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->showEditModal = false;
        session()->flash('message', 'User updated successfully!');
        $this->resetForm();
    }


    public function confirmDelete($get_id)
    {
        try {
            $user = User::find($get_id);

            if ($user) {
                // Hapus foto profil jika ada
                if ($user->profile_photo) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                // Hapus data user
                $user->delete();

                session()->flash('message', 'User deleted successfully!');
            } else {
                session()->flash('error', 'User not found!');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
        }
    }



    public function render()
    {
        $users = User::latest()->paginate($this->perPage);
        return view('livewire.users.user-list', [
            'users' => $users
        ]);
    }
}
