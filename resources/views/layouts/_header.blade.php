<nav class="global-header navbar navbar-expand-md navbar-dark bg-dark mb-3">
    <a class="navbar-brand" href="#">{{ $app_name }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#globalNavbar" aria-controls="globalNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="globalNavbar">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">{{ __('foo') }}</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="#">{{ __('bar') }}</a>
            </li>
        </ul>
        @if(Auth::check())
            <div class="logged-in-user-dropdown dropdown ml-2 fx2">
                <div class="btn-group logged-in-user-dropdown text-light">
                    <div class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ $login_user->name }}
                    </div>
                    <div class="dropdown-menu dropdown-menu-right">
                        <button class="dropdown-item" type="button"><a href="#" class="text-dark">
                                <i class="far fa-user"></i>
                                ユーザー情報
                            </a></button>
                        <button class="dropdown-item text-dark" type="button" id="logoutLink">
                            <form action="{{ route('logout', [], false) }}" method="post" id="logoutForm">@csrf</form>
                            <i class="fas fa-sign-out-alt"></i>
                            ログアウト
                        </button>
                    </div>
                </div>
            </div>
        @else
            <a class="btn btn-outline-light mr-2" href="{{ route('register', [], false) }}">Sign up</a>
            <a class="btn btn-outline-light" href="{{ route('login', [], false) }}">Login</a>
        @endif
    </div>
</nav>