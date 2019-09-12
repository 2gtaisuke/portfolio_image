@extends('layouts/app')

@section('title', $user->name)

@section('content')
<form action="{{ route('user.update', ['id' => $user->id], false) }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-header">設定</div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a class="stretched-link text-decoration-none text-dark" href="{{ route('user.edit', ['id' => $user->id], false) }}">ユーザー情報変更</a>
                        </li>
                        <li class="list-group-item">
                            <a class="stretched-link text-decoration-none text-dark" href="#" data-toggle="modal" data-target="#modalLeaveUser">退会</a>
                        </li>
                    </ul>
            </div>
        </div>
        <div class="col-9">
            <h2 class="border-bottom mb-3">ユーザー情報</h2>
            <div class="row">
                <div class="col-8">
                    <div class="form-group row">
                        <label for="inputUserName" class="col-sm-4 col-form-label">ユーザー名</label>
                        <div class="col-sm-8">
                            <input type="text" name="user[name]" class="form-control @error('user.name') is-invalid @enderror" id="inputUserName" placeholder="ユーザー名" value="{{ old('user.name', $user->name) }}">
                            @if($errors->has('user.name'))
                                <div class="invalid-feedback">
                                    {{ $errors->get('user.name')[0] }}
                                </div>
                            @else
                                <small id="emailHelp" class="form-text text-muted">5~12文字の範囲で入力してください</small>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputExposeEmail" class="col-sm-4 col-form-label">メールアドレス公開</label>
                        <div class="col-sm-8">
                            <input type="checkbox" name="user[expose_email]" class="form-control" id="inputExposeEmail" value="{{ old('user.expose_email', $user->expose_email) }}" @if($user->isExposeEmail()) checked @endif>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <img src="{{ get_user_profile_image($user->profile_image) }}" class="img-fluid" alt="user_profile_image">
                    <div class="form-group">
                        <label for="inputUserImage">ユーザー画像変更</label>
                        <input type="file" name="user[profile_image]" class="form-control-file @error('user.profile_image') is-invalid @enderror" id="inputUserImage">
                        @if($errors->has('user.profile_image'))
                            <div class="invalid-feedback">
                                {{ $errors->get('user.profile_image')[0] }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="user-edit-button-wrapper text-center">
        <button class="btn btn-primary mt-2 form-button">
            変更する
        </button>
    </div>
</form>
<div class="modal fade" id="modalLeaveUser" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="form" action="{{ route('user.destroy', ['id' => $user->id], false) }}" method="post" id="deleteUserForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">退会</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <input type="hidden" name="username" value="{{ $user->name }}" id="hiddenUserName">
                        本当に退会しますか？<br>
                        確認のためユーザー名( <strong>{{ $user->name }}</strong> )を入力してください。
                        <div class="form-group">
                            <input type="text" class="form-control" name="username" id="inputLeaveUsername">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger form-button" id="leaveUserButton" disabled>退会する</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection