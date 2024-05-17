@extends('layouts.app')

@section('titulo')
    Página pricipal
@endsection

@section('contenido')
    <x-listar-post :posts="$posts" />
@endsection
