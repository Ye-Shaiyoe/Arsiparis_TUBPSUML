@extends('errors.layout')

@section('title', 'Terlalu Banyak Permintaan Untuk Saat Ini')
@section('code', '429')
@section('message', 'Terlalu Banyak Permintaan')
@section('description', 'Maaf, Anda mengirimkan terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba kembali.')
