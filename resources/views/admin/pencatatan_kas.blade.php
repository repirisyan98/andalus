@extends('layouts.app')
@section('title', 'Pencatatan Kas')

@section('style')
@endsection

@section('wrapper')
    @livewire('admin.pencatatan-kas');
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
