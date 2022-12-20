<?php

namespace App\Http\Livewire\Admin;

use App\Models\Kas;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class PencatatanKas extends Component
{
    use LivewireAlert;
    use WithPagination;

    public $readyToLoad, $temp_id, $tanggal, $jenis, $jumlah, $keterangan, $filter_tanggal, $filter_tanggal_format;

    protected $listeners = [
        'confirmed',
        'canceled'
    ];

    protected $rules = [
        'tanggal' => 'date|required',
        'jenis' => 'required',
        'jumlah' => 'numeric|required',
        'keterangan' => 'nullable',
    ];

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
        return view('livewire.admin.pencatatan-kas', [
            'data' => $this->readyToLoad ? Kas::when($this->filter_tanggal_format != null, function ($query) {
                return $query->whereMonth('tanggal', date_format($this->filter_tanggal_format, 'm'))->whereYear('tanggal', date_format($this->filter_tanggal_format, 'Y'));
            })->simplePaginate(15) : []
        ]);
    }

    public function resetFields()
    {
        $this->resetValidation();
        $this->resetExcept('readyToLoad', 'filter_tanggal', 'filter_tanggal_format');
    }

    public function updatedFilterTanggal()
    {
        $this->filter_tanggal_format = date_create($this->filter_tanggal);
    }


    public function store()
    {
        $this->validate();
        try {
            Kas::create([
                'tanggal' => $this->tanggal,
                'jenis' => $this->jenis,
                'jumlah' => $this->jumlah,
                'keterangan' => $this->keterangan
            ]);
            $this->alert('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            $this->alert('error', 'Terjadi kesalahan saat menyimpan data');
        }
        $this->dispatchBrowserEvent('store');
        $this->emit('store');
        $this->resetFields();
    }

    public function edit($id)
    {
        $data = Kas::where('id', $id)->select('tanggal', 'jenis', 'jumlah', 'keterangan')->first();
        $this->temp_id = $id;
        $this->tanggal = $data->tanggal;
        $this->jenis = $data->jenis;
        $this->jumlah = $data->jumlah;
        $this->keterangan = $data->keterangan;
    }

    public function update()
    {
        $this->validate();
        try {
            Kas::find($this->temp_id)->update([
                'tanggal' => $this->tanggal,
                'jenis' => $this->jenis,
                'jumlah' => $this->jumlah,
                'keterangan' => $this->keterangan,
                'updated_at' => now(),
            ]);
            $this->alert('success', 'Data berhasil diubah');
        } catch (\Throwable $th) {
            $this->alert('error', 'Terjadi kesalahan saat mengubah data');
        }
        $this->dispatchBrowserEvent('update');
        $this->emit('update');
        $this->resetFields();
    }

    public function triggerConfirm($id)
    {
        $this->confirm('Hapus data ini ?', [
            'toast' => false,
            'position' => 'center',
            'confirmButtonText' =>  'Hapus',
            'cancelButtonText' =>  'Batal',
            'onConfirmed' => 'confirmed',
            // 'onConfirmed' => ['confirmed', $id], you can pass argument with array
            'onDismissed' => 'cancelled'
        ]);
        $this->temp_id = $id;
    }

    public function confirmed()
    {
        try {
            Kas::destroy($this->temp_id);
            $this->alert(
                'success',
                'Data berhasil dihapus'
            );
        } catch (\Throwable $th) {
            $this->alert(
                'error',
                'Terjadi kesalahan saat menghapus data'
            );
        }
        $this->resetFields();
    }

    public function cancelled()
    {
        $this->resetFields();
    }
}