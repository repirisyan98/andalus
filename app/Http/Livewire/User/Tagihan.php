<?php

namespace App\Http\Livewire\User;

use App\Models\TagihanPelanggan;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Tagihan extends Component
{
    use WithPagination;
    use WithFileUploads;
    use LivewireAlert;

    public $readyToLoad, $temp_id, $bukti_transfer, $filter_status, $filter_tanggal, $filter_tanggal_format;

    public function mount()
    {
        $this->readyToLoad = false;
    }

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.user.tagihan', [
            'data' => $this->readyToLoad ? TagihanPelanggan::when($this->filter_tanggal_format != null, function ($query) {
                return $query->whereMonth('tanggal', date_format($this->filter_tanggal_format, 'm'))->whereYear('tanggal', date_format($this->filter_tanggal_format, 'Y'));
            })->when($this->filter_status != null, function ($query) {
                return $query->where('status', $this->filter_status);
            })->where('user_id', auth()->user()->id)->orderBy('tanggal', 'desc')->simplePaginate(15) : []
        ]);
    }

    public function filterStatus($status)
    {
        $this->filter_status = $status;
        $this->resetPage();
    }

    public function updatedFilterTanggal()
    {
        if ($this->filter_tanggal != null) {
            $this->filter_tanggal_format = date_create($this->filter_tanggal);
        } else {
            $this->filter_tanggal_format = null;
        }
    }

    public function resetFields()
    {
        $this->resetValidation();
        $this->resetExcept('readyToLoad', 'filter_tanggal', 'filter_tanggal_format');
    }

    public function update()
    {
        $this->validate([
            'bukti_transfer' => 'image|required'
        ]);
        $extension = $this->bukti_transfer->extension();
        $filename = date('d_m_Y') . $this->temp_id . '.' . $extension;
        try {
            TagihanPelanggan::find($this->temp_id)->update([
                'tanggal_bayar' => date('Y-m-d'),
                'bukti_transfer' => $filename
            ]);
            $this->bukti_transfer->storeAs('public/bukti_transfer', $filename);
            $this->alert('success', 'Upload data berhasil');
        } catch (\Throwable $th) {
            $this->alert('error', 'Terjadi kesalahan saat upload data');
        }
        $this->dispatchBrowserEvent('update');
        $this->emit('update');
        $this->resetFields();
    }
}