@extends('layouts.admin')

@section('title', 'User List')

@section('content')

<div class="row">
    <div class="col-md-12">

        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        <div class="card">
            <div class="card-header">
                <h3>Users
                    <a href="{{ url('admin/users/create') }}" class="btn btn-primary btn-sm float-end">Add Products</a>
                </h3>
            </div>
            <div class="card-body">
                