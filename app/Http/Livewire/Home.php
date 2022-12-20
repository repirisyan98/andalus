<?php

namespace App\Http\Livewire;

use App\Models\Saldo;
use App\Models\TagihanPelanggan;
use App\Models\User;
use Livewire\Component;

class Home extends Component
{
    public $readyToLoad;

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
        if (auth()->user()->role == '1') {
            return view('livewire.home', [
                'user' => $this->readyToLoad ? User::where('role', '2')->count() : [],
                'tagihan' => $this->readyToLoad ? TagihanPelanggan::select('meteran_awal', 'meteran_akhir', 'tarif', 'biaya_admin', 'status', 'bukti_transfer')->get() : [],
                'saldo' => $this->readyToLoad ? Saldo::find(1)->value('saldo') : [],
            ]);
        } else {
            return view('livewire.home', [
                'tagihan' => $this->readyToLoad ? TagihanPelanggan::select('status')->where('user_id', auth()->user()->id)->get() : [],
                'tunggakan' => $this->readyToLoad ? TagihanPelanggan::where('user_id', auth()->user()->id)->where('status', false)->get() : [],
            ]);
        }
    }
}