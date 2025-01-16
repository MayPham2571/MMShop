@extends('layouts.app')

@section('title','Thank You for Shopping')

@section('content')

<div class="py-3 pyt-md-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">

                @if (session('message'))
                    <h5 class="alert alert-success">{{ session('message') }}</h5>
                @endif

                <img src="{{ asset('assets/MMShop.png') }}" alt="Logo">
                <hr>
                <h4>Thank You for Shopping at MMShop</h4>
                <a href="{{ url('collections') }}" class="btn btn-primary">Shop now</a>
            </div>
        </div>
    </div>
</div>

    
@endsection