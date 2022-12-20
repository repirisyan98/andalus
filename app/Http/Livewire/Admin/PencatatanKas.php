<?php

namespace App\Http\Livewire\Admin;

use App\Models\Kas;
use App\Models\Saldo;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class PencatatanKas extends Component
{
    use LivewireAlert;
    use WithPagination;

    public $readyToLoad, $temp_id, $tanggal, $jenis, $jumlah, $keterangan, $saldo, $filter_tanggal, $filter_tanggal_format, $filter_status;

    // protected $listeners = [
    //     'confirmed',
    //     'canceled'
    // ];

    protected $rules = [
        'tanggal' => 'date|required',
        'jenis' => 'required',
        'jumlah' => 'numeric|required',
        'keterangan' => 'nullable',
    ];

    public function mount()
    {
        $this->readyToLoad = false;
        $this->filter_status = '3';
    }

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.admin.pencatatan-kas', [
            'data' => $this->readyToLoad ? Kas::when($this->filter_status != '3', function ($query) {
                return $query->where('jenis', $this->filter_status);
            })->when($this->filter_tanggal_format != null, function ($query) {
                return $query->whereMonth('tanggal', date_format($this->filter_tanggal_format, 'm'))->whereYear('tanggal', date_format($this->filter_tanggal_format, 'Y'));
            })->simplePaginate(15) : []
        ]);
    }

    public function resetFields()
    {
        $this->resetValidation();
        $this->resetExcept('readyToLoad', 'filter_tanggal', 'filter_tanggal_format');
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


    public function store()
    {
        $this->validate();
        try {
            $saldo_awal = Saldo::where('id', '1')->value('saldo');
            if ($this->jenis == 1) {
                $this->saldo = $saldo_awal + $this->jumlah;
            } else {
                $this->saldo = $saldo_awal - $this->jumlah;
            }
            DB::transaction(function () {
                Saldo::find(1)->update([
                    'saldo' => $this->saldo
                ]);
                Kas::create([
                    'tanggal' => $this->tanggal,
                    'jenis' => $this->jenis,
                    'jumlah' => $this->jumlah,
                    'saldo_akhir' => $this->saldo,
                    'keterangan' => $this->keterangan
                ]);
            });

            $this->alert('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            $this->alert('error', 'Terjadi kesalahan saat menyimpan data');
        }
        $this->dispatchBrowserEvent('store');
        $this->emit('store');
        $this->resetFields();
    }

    // public function edit($id)
    // {
    //     $data = Kas::where('id', $id)->select('tanggal', 'jenis', 'jumlah', 'keterangan')->first();
    //     $this->temp_id = $id;
    //     $this->tanggal = $data->tanggal;
    //     $this->jenis = $data->jenis;
    //     $this->jumlah = $data->jumlah;
    //     $this->keterangan = $data->keterangan;
    // }

    // public function update()
    // {
    //     $this->validate();
    //     try {
    //         Kas::find($this->temp_id)->update([
    //             'tanggal' => $this->tanggal,
    //             'jenis' => $this->jenis,
    //             'jumlah' => $this->jumlah,
    //             'keterangan' => $this->keterangan,
    //             'updated_at' => now(),
    //         ]);
    //         $this->alert('success', 'Data berhasil diubah');
    //     } catch (\Throwable $th) {
    //         $this->alert('error', 'Terjadi kesalahan saat mengubah data');
    //     }
    //     $this->dispatchBrowserEvent('update');
    //     $this->emit('update');
    //     $this->resetFields();
    // }

    // public function triggerConfirm($id)
    // {
    //     $this->confirm('Hapus data ini ?', [
    //         'toast' => false,
    //         'position' => 'center',
    //         'confirmButtonText' =>  'Hapus',
    //         'cancelButtonText' =>  'Batal',
    //         'onConfirmed' => 'confirmed',
    //         // 'onConfirmed' => ['confirmed', $id], you can pass argument with array
    //         'onDismissed' => 'cancelled'
    //     ]);
    //     $this->temp_id = $id;
    // }

    // public function confirmed()
    // {
    //     try {
    //         Kas::destroy($this->temp_id);
    //         $this->alert(
    //             'success',
    //             'Data berhasil dihapus'
    //         );
    //     } catch (\Throwable $th) {
    //         $this->alert(
    //             'error',
    //             'Terjadi kesalahan saat menghapus data'
    //         );
    //     }
    //     $this->resetFields();
    // }

    public function cancelled()
    {
        $this->resetFields();
    }
}