@extends('layouts.app')
@section('title', 'Detail Tagihan Pelanggan')

@section('style')
@endsection

@section('wrapper')
    @livewire('admin.detail-tagihan-pelanggan', ['id' => $id]);
@endsection
