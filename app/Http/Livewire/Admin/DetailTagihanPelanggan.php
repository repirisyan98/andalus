<?php

namespace App\Http\Livewire\Admin;

use App\Models\TagihanPelanggan;
use Livewire\Component;
use Livewire\WithPagination;

class DetailTagihanPelanggan extends Component
{
    use WithPagination;

    public $readyToLoad, $user_id, $filter_status, $filter_tanggal;

    public function mount($id)
    {
        $this->user_id = $id;
        $this->readyToLoad = false;
        $this->filter_status = '2';
    }

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.admin.detail-tagihan-pelanggan', [
            'data' => $this->readyToLoad ? TagihanPelanggan::when($this->filter_tanggal != null, function ($query) {
                return $query->where('tanggal', $this->filter_tanggal);
            })->when($this->filter_status != '2', function ($query) {
                return $query->where('status', $this->filter_status);
            })->where('user_id', $this->user_id)->orderBy('tanggal', 'asc')->simplePaginate(15) : []
        ]);
    }

    public function filterStatus($status)
    {
        $this->filter_status = $status;
        $this->resetPage();
    }

    // public function updatedFilterTanggal()
    // {
    //     if ($this->filter_tanggal != null) {
    //         $this->filter_tanggal_format = date_create($this->filter_tanggal);
    //     } else {
    //         $this->filter_tanggal_format = null;
    //     }
    // }
}