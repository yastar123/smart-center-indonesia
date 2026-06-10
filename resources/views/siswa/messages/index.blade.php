@extends('layouts.app')
@section('title','Pesan - Siswa')
@section('page-title','Pesan')

@section('content')
@php
    $messageBaseUrl = url('siswa/messages');
    $messageCreateRoute = route('siswa.messages.createRoom');
    $allowCreateRoom = false;
@endphp
@include('admin.messages.index')
@endsection
