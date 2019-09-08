@extends('layouts/app')

@section('title', $user->name)

@section('content')
    <div class="card">
        <div class="card-header">ユーザー情報</div>
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item">{{ $user->name }}</li>
                <li class="list-group-item">{{ $user->email }}</li>
            </ul>
        </div>
    </div>
@endsection