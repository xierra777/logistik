<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class UserView extends Component
{
    public $users;
    public function mount($id)
    {
        $this->users = User::find($id);
    }
    public function render()
    {
        return view('livewire.users.user-view');
    }
}
