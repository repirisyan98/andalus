@extends('layouts.app')
@section('title', 'Data Pelanggan')

@section('style')
@endsection

@section('wrapper')
    @livewire('admin.data-pelanggan');
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
