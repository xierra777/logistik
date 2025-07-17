<?php

namespace App\Livewire\Users;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class UserList extends Component
{
    use WithFileUploads, WithPagination;

    public $name, $email, $password, $profile_photo, $role, $existing_photo;
    public $perPage = 5;
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
            'created_by'    => Auth::user()->id,
        ]);

        // dd($photoPath);

        // Refresh data users in table
        session()->flash('message', 'User created successfully.');
        $this->resetForm();
        // $this->dispatch('close-modal');
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
        $user = User::findOrFail($id);
        $this->selectedUserId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        // Reset upload baru
        $this->profile_photo = null;

        // Simpan foto existing
        $this->existing_photo = $user->profile_photo;

        $this->password = '';
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
        $user->updated_by = Auth::user()->id;
        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->update();

        $this->dispatch('close-edit');
        session()->flash('message', 'User updated successfully!');
        $this->resetForm();
    }


    public function confirmDelete($get_id)
    {
        try {
            logger('Masuk delete: ' . $get_id);
            $user = User::find($get_id);

            if ($user) {
                logger('User ditemukan: ' . $user->id);

                if ($user->profile_photo) {
                    logger('Menghapus foto: ' . $user->profile_photo);
                    Storage::disk('public')->delete($user->profile_photo);
                }
                DB::table('t_jobs')
                    ->where('employee_id', $user->id)
                    ->update([
                        'employee_id' => null,
                    ]);
                DB::table('t_shipments')
                    ->where('employee_id', $user->id)
                    ->update([
                        'employee_id' => null,
                    ]);
                $user->delete();
                logger('User berhasil dihapus dari DB');

                session()->flash('message', 'User deleted successfully!');
            } else {
                logger('User tidak ditemukan');
                session()->flash('error', 'User not found!');
            }
        } catch (\Exception $e) {
            logger('Error: ' . $e->getMessage());
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
        }
    }




    public function render()
    {
        $users = User::with('creator')->latest()->paginate($this->perPage);
        return view('livewire.users.user-list', [
            'users' => $users
        ]);
    }
}
