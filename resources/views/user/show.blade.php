@extends('layouts/app')

@section('title', $user->name)

@section('content')
<div class="card">
    <div class="card-header">ユーザー情報</div>
    <div class="card-body">
        <div class="row">
            <div class="col-4 text-center mt-2">
                <img src="{{ get_user_profile_image($user->profile_image) }}" class="img-fluid" alt="user_profile_image">
                @if($login_user && !$login_user->isMyself($user))
                    <div class="follow-button-wrapper text-center mt-2">
                        @if($login_user->isFollowing($user))
                            <button type="button" id="followUserBtn" class="btn btn-secondary mt-2" data-follow="{{ $user->id }}">フォローを外す</button>
                        @else
                            <button type="button" id="followUserBtn" class="btn btn-primary mt-2" data-follow="{{ $user->id }}">フォローする</button>
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-8">
                <table class="table table-borderd">
                    <thead>
                    <tr>
                        <th scope="row">name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">email</th>
                        <td>
{{--                            TODO: ユーザー設定でアドレス開示を選べるようにする --}}
                            非表示
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-4">
        <div class="card">
            <div class="card-header">フォロワー（{{ $followers->count() }}）</div>
            <div class="card-body">
                @foreach($followers as $follower)
                    <a href="{{ route('user.show', ['id' => $follower->id], false) }}">
                        <img class="rounded-circle" src="{{ get_user_profile_image($follower->profile_image) }}" alt="" height="35" width="35" data-toggle="tooltip" data-placement="top" title="Tooltip on top">
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">投稿</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#like" role="tab" aria-controls="like" aria-selected="false">いいね</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-lg-3 col-sm-12 mt-lg-4 mt-sm-1">
                                <div class="card image-card">
                                    <img src="{{ asset('storage/unknown_user.jpeg') }}" class="card-img-top" alt="...">
                                    <a href="#" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="like" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-lg-3 col-sm-12 mt-lg-4 mt-sm-1">
                                <div class="card image-card">
                                    <img src="{{ asset('storage/unknown_user.jpeg') }}" class="card-img-top" alt="...">
                                    <a href="#" class="stretched-link"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection