<?php

namespace App\Livewire;

use Livewire\Component;

class Pdfhbl extends Component
{
    public $contaiers;


    public function mount()
    {
        $referer = request()->headers->get('referer');

        if (!str_contains($referer, 'view-shipments')) {
            abort(403, 'No direct access allowed.');
        }
    }
    public function render()
    {
        return view('livewire.pdfhbl');
    }
}
