@include('layouts.header')

<body id="login" class="text-center bg-light">
    <main class="m-auto bg-white border p-5">
        <form action="{{ route('login') }}" method="post">
            @csrf
            <a href="{{ route('index') }}">
                <img class="mb-5" src="{{ asset('img/logo.gif') }}">
            </a>

            @if (\Session::has('header') && \Session::has('message'))
                <div class="alert alert-danger text-start">
                    <h2>{{ \Session::get('header') }}</h2>
                    <p>{{ \Session::get('message') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
                </div>
            @endif

            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                <label for="email">{{ _gettext('Email') }}</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="password" name="password" placeholder="{{ _gettext('Password') }}">
                <label for="password">{{ _gettext('Password') }}</label>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" class="me-1" name="remember-me" value="1">{{ _gettext('Remember Me') }}
                </label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">{{ _gettext('Sign In') }}</button>
        </form>
        <p class="mt-3">
            <a href="{{ route('password.request') }}">{{ _gettext('Forgot Password') }}</a>
            @if (env('FCMS_ALLOW_REGISTRATION'))
            | <a href="{{ route('register') }}">{{ _gettext('Register') }}</a>
            @endif
        </p>
    </main>
</body>
</html>
