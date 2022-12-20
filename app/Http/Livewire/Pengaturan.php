<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Pengaturan extends Component
{
    use LivewireAlert;

    public $password, $password_confirmation;

    public function render()
    {
        return view('livewire.pengaturan');
    }

    public function change_password()
    {
        $this->validate([
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password'
        ]);
        try {
            User::find(auth()->user()->id)->update([
                'password' => Hash::make($this->password),
                'updated_at' => now()
            ]);
            $this->alert(
                'success',
                "Password berhasil diubah"
            );
        } catch (\Throwable $th) {
            $this->alert(
                'error',
                "Terjadi kesalahan saat mengubah data"
            );
        }
        $this->resetValidation();
        $this->reset();
    }
}