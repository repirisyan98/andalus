<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DataPelanggan extends Component
{
    use WithPagination;
    use LivewireAlert;

    protected $paginationTheme = 'bootstrap';

    public $readyToLoad, $temp_id, $search, $nomor_rumah, $name, $alamat;

    protected $listeners = [
        'confirmed',
        'canceled'
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
        return view('livewire.admin.data-pelanggan', [
            'data' => $this->readyToLoad ? User::when($this->search != null, function ($query) {
                return $query->where('username', 'like', '%' . $this->search . '%');
            })->where('role', '2')->simplePaginate(10) : [],
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFields()
    {
        $this->resetValidation();
        $this->resetExcept('readyToLoad', 'search');
    }

    public function store()
    {
        $this->validate([
            'nomor_rumah' => 'required|unique:users,username',
            'name' => 'required',
            'alamat' => 'nullable'
        ]);
        try {
            User::create([
                'username' => $this->nomor_rumah,
                'name' => $this->name,
                'alamat' => $this->alamat,
                'password' => Hash::make('12345678'),
                'role' => 2
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
        $data = User::where('id', $id)->select('username', 'name', 'alamat')->first();
        $this->temp_id = $id;
        $this->name = $data->name;
        $this->alamat = $data->alamat;
        $this->nomor_rumah = $data->username;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'alamat' => 'nullable'
        ]);
        try {
            User::find($this->temp_id)->update([
                'username' => $this->nomor_rumah,
                'name' => $this->name,
                'alamat' => $this->alamat,
                'updated_at' => now(),
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
            User::destroy($this->temp_id);
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