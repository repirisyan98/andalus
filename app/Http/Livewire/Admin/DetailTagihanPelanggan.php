<?php

namespace App\Http\Livewire\Admin;

use App\Models\TagihanPelanggan;
use Livewire\Component;
use Livewire\WithPagination;

class DetailTagihanPelanggan extends Component
{
    use WithPagination;

    public $readyToLoad, $user_id, $filter_status;

    public function mount($id)
    {
        $this->user_id = $id;
        $this->readyToLoad = false;
    }

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.admin.detail-tagihan-pelanggan', [
            'data' => $this->readyToLoad ? TagihanPelanggan::when($this->filter_status != null, function ($query) {
                return $query->where('status', $this->filter_status);
            })->where('user_id', $this->user_id)->simplePaginate(15) : []
        ]);
    }

    public function filterStatus($status)
    {
        $this->filter_status = $status;
        $this->resetPage();
    }
}