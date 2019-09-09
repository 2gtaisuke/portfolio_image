<div class="text-center my-2">
    or
</div>
<div class="card login-social">
    <div class="card-body text-center">
        <a href="{{ route('social.login', ['provider' => 'github'], false) }}" class="text-dark mr-3"><i class="fab fa-github fa-2x login-github"></i></a>
        <a href="{{ route('social.login', ['provider' => 'google'], false) }}" class="text-dark mr-3"><i class="fab fa-google fa-2x login-google"></i></a>
        <a href="{{ route('social.login', ['provider' => 'twitter'], false) }}" class="text-dark"><i class="fab fa-twitter fa-2x login-twitter"></i></a>
    </div>
</div>