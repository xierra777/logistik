<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UserView extends Component
{
    public function mount($id)
    {
        $users = User::get($id);
    }
    public function render()
    {
        return view('livewire.users.user-view');
    }
}
