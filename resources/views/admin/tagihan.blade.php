@extends('layouts.app')
@section('title', 'Tagihan Pelanggan')

@section('style')
@endsection

@section('wrapper')
    @livewire('admin.tagihan',['status' => $status]);
@endsection

@section('script')
    <script>
        window.livewire.on('store', () => {
            $('#modalTambah').modal('hide');
        });
        window.livewire.on('update', () => {
            $('#modalUbah').modal('hide');
        });
    </script>
@endsection
