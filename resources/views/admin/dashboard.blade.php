@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h2>Welcome Admin, {{ $user->name }}!</h2>
    <p>Email: {{ $user->email }}</p>
    <p>Kontak: {{ $user->kontak }}</p>
    <p>Alamat: {{ $user->alamat }}</p>


@endsection
