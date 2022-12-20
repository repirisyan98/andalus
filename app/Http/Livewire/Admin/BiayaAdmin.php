<?php

namespace App\Http\Livewire\Admin;

use App\Models\BiayaAdmin as ModelsBiayaAdmin;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BiayaAdmin extends Component
{
    use LivewireAlert;

    public $readyToLoad, $temp_id, $biaya_admin, $tarif;

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
        return view('livewire.admin.biaya-admin', [
            'data' => $this->readyToLoad ? ModelsBiayaAdmin::all() : [],
        ]);
    }

    public function resetFields()
    {
        $this->resetValidation();
        $this->resetExcept('readyToLoad');
    }

    public function edit($id)
    {
        $data = ModelsBiayaAdmin::select('biaya_admin', 'tarif')->first();
        $this->biaya_admin = $data->biaya_admin;
        $this->tarif = $data->tarif;
        $this->temp_id = $id;
    }

    public function update()
    {
        $this->validate([
            'biaya_admin' => 'required|numeric',
            'tarif' => 'required|numeric'
        ]);
        try {
            ModelsBiayaAdmin::find($this->temp_id)->update([
                'biaya_admin' => $this->biaya_admin,
                'tarif' => $this->tarif,
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
}