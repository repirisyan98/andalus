<?php

namespace App\Http\Livewire\Admin;

use App\Models\BiayaAdmin;
use App\Models\TagihanPelanggan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Tagihan extends Component
{
    use LivewireAlert;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $readyToLoad, $temp_id, $user_id, $search, $bukti_transfer, $biaya_admin, $filter_status, $filter_tanggal, $filter_tanggal_format, $tarif, $nomor_rumah, $nama_pelanggan, $tanggal, $meteran_awal, $meteran_akhir, $pemakaian, $tagihan, $tanggal_bayar, $status;

    protected $listeners = [
        'confirmed', 'canceled', 'accepted', 'denied'
    ];

    protected $rules = [
        'user_id' => 'required',
        'tanggal' => 'required|date',
        'meteran_awal' => 'required|numeric',
        'meteran_akhir' => 'required|numeric',
        'status' => 'required',
        'biaya_admin' => 'required|numeric',
        'tarif' => 'required|numeric',
        'tarif' => 'required|numeric',
    ];

    public function mount()
    {
        $this->readyToLoad = false;
        $data = BiayaAdmin::select('biaya_admin', 'tarif')->first();
        $this->biaya_admin = $data->biaya_admin;
        $this->tarif = $data->tarif;
        $this->filter_status = '2';
    }

    public function loadPosts()
    {
        $this->readyToLoad = true;
    }

    public function render()
    {
        return view('livewire.admin.tagihan', [
            'data' => $this->readyToLoad ? TagihanPelanggan::with('user')->when($this->filter_tanggal_format != null, function ($query) {
                return $query->whereMonth('tanggal', date_format($this->filter_tanggal_format, 'm'))->whereYear('tanggal', date_format($this->filter_tanggal_format, 'Y'));
            })->when($this->search != null, function ($query) {
                return $query->whereHas('user', function ($query) {
                    return $query->where('username', 'like', '%' . $this->search . '%');
                });
            })->when($this->filter_status != '2', function ($query) {
                return $query->where('status', $this->filter_status);
            })->simplePaginate(15) : [],
            'data_rumah' => $this->readyToLoad ? User::select('username')->where('role', '2')->get() : [],
        ]);
    }

    public function updatedFilterTanggal()
    {
        if ($this->filter_tanggal != null) {
            $this->filter_tanggal_format = date_create($this->filter_tanggal);
        } else {
            $this->filter_tanggal_format = null;
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function filterStatus($status)
    {
        $this->filter_status = $status;
        $this->resetPage();
    }

    public function resetFields()
    {
        $this->resetValidation();
        $this->resetExcept('readyToLoad', 'biaya_admin', 'tarif', 'search', 'filter_tanggal_format', 'filter_tanggal', 'filter_status');
    }

    public function updatedNomorRumah()
    {
        if ($this->nomor_rumah != null) {
            $data = User::select('id', 'name')->where('username', $this->nomor_rumah)->first();
            $this->nama_pelanggan = $data->name;
            $this->user_id = $data->id;
            $this->meteran_awal = TagihanPelanggan::whereHas('user', function ($query) {
                return $query->where('username', $this->nomor_rumah);
            })->orderBy('tanggal', 'desc')->limit(1)->value('meteran_akhir');
            $this->calculate();
        }
    }

    public function calculate()
    {
        if ($this->meteran_akhir >= 0  && $this->meteran_awal >= 0) {
            $this->pemakaian = $this->meteran_akhir - $this->meteran_awal;
            $this->tagihan = ($this->tarif * $this->pemakaian) + $this->biaya_admin;
        }
    }

    public function store()
    {
        $this->validate();
        try {
            TagihanPelanggan::create([
                'user_id' => $this->user_id,
                'tanggal' => $this->tanggal,
                'meteran_awal' => $this->meteran_awal,
                'meteran_akhir' => $this->meteran_akhir,
                'tarif' => $this->tarif,
                'biaya_admin' => $this->biaya_admin,
                'tanggal_bayar' => $this->tanggal_bayar,
                'status' => $this->status,
            ]);
            $this->alert(
                'success',
                "Data Berhasil Disimpan"
            );
        } catch (\Throwable $th) {
            $this->alert(
                'error',
                "Terjadi kesalahan saat menyimpan data"
            );
        }
        $this->dispatchBrowserEvent('store');
        $this->emit('store');
        $this->resetFields();
    }

    public function edit($id)
    {
        $data = TagihanPelanggan::with('user')->where('id', $id)->first();
        $this->temp_id = $id;
        $this->user_id = $data->user_id;
        $this->nomor_rumah = $data->user->username;
        $this->nama_pelanggan = $data->user->name;
        $this->tanggal = $data->tanggal;
        $this->meteran_awal = $data->meteran_awal;
        $this->meteran_akhir = $data->meteran_akhir;
        $this->calculate();
        $this->tanggal_bayar = $data->tanggal_bayar;
        $this->status = $data->status;
    }

    public function update()
    {
        // $this->validate();
        try {
            TagihanPelanggan::find($this->temp_id)->update([
                'user_id' => $this->user_id,
                'tanggal' => $this->tanggal,
                'meteran_awal' => $this->meteran_awal,
                'meteran_akhir' => $this->meteran_akhir,
                'tarif' => $this->tarif,
                'biaya_admin' => $this->biaya_admin,
                'tanggal_bayar' => $this->tanggal_bayar,
                'status' => $this->status,
                'updated_at' => now()
            ]);
            $this->alert(
                'success',
                "Data Berhasil Diubah"
            );
        } catch (\Throwable $th) {
            $this->alert(
                'error',
                "Terjadi kesalahan saat mengubah data"
            );
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
            TagihanPelanggan::destroy($this->temp_id);
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

    public function triggerValidate($id, $bukti_transfer)
    {
        $this->confirm('Konfirmasi Data ?', [
            'toast' => false,
            'position' => 'center',
            'showConfirmButton' => true,
            'showDenyButton' => true,
            'showCancelButton' => false,
            'confirmButtonText' =>  'Terima',
            'denyButtonText' => 'Tolak',
            'onConfirmed' => 'accepted',
            'onDenied' => 'denied',
            // 'onConfirmed' => ['confirmed', $id], you can pass argument with array
            'onDismissed' => 'cancelled'
        ]);
        $this->temp_id = $id;
        $this->bukti_transfer = $bukti_transfer;
    }

    public function accepted()
    {
        try {
            TagihanPelanggan::find($this->temp_id)->update([
                'status' => true
            ]);
            $this->alert(
                'success',
                'Konfirmasi diterima'
            );
        } catch (\Throwable $th) {
            $this->alert(
                'error',
                'Terjadi kesalahan saat mengkonfirmasi data'
            );
        }
        $this->resetFields();
    }

    public function denied()
    {
        try {
            TagihanPelanggan::find($this->temp_id)->update([
                'tanggal_bayar' => null,
                'bukti_transfer' => null
            ]);
            Storage::delete('public/bukti_transfer/' . $this->bukti_transfer);
            $this->alert(
                'success',
                'Konfirmasi ditolak'
            );
        } catch (\Throwable $th) {
            $this->alert(
                'error',
                'Terjadi kesalahan saat mengkonfirmasi data'
            );
        }
        $this->resetFields();
    }
}