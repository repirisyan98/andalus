@extends('layouts.app')
@section('title', 'Biaya Admin')

@section('style')
@endsection

@section('wrapper')
    @livewire('admin.biaya-admin');
@endsection

@section('script')
    <script>
        window.livewire.on('update', () => {
            $('#modalUbah').modal('hide');
        });
    </script>
@endsection
