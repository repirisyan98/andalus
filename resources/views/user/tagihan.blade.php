@extends('layouts.app')
@section('title', 'Riwayat Tagihan')

@section('style')
@endsection

@section('wrapper')
    @livewire('user.tagihan');
@endsection

@section('script')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        window.livewire.on('update', () => {
            $('#modalUbah').modal('hide');
        });
    </script>
@endsection
