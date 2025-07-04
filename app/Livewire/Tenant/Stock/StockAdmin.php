<?php

namespace App\Livewire\Tenant\Stock;

use Livewire\Attributes\On;
use Livewire\Component;


class StockAdmin extends Component
{
    #[On('updateTable')]
    public function render()
    {
        return view('livewire.tenant.stock.stock-admin');
    }
}
