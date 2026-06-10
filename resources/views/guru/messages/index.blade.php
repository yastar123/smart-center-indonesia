@extends('layouts.app')
@section('title','Pesan - Guru')
@section('page-title','Pesan')

@section('content')
@php
    $messageBaseUrl = url('guru/messages');
    $messageCreateRoute = route('guru.messages.createRoom');
    $allowCreateRoom = false;
@endphp
@include('admin.messages.index')
@endsection
