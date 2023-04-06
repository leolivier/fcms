@include('layouts.header')

<body id="install" class="bg-light">
    <main class="m-auto bg-white border p-5">
        <div>
            <img class="mb-5" src="{{ asset('img/logo.gif') }}">
            <div class="progress mb-3">
                <div class="progress-bar" style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="alert alert-info">
                <h4 class="alert-heading">{{ _gettext('3. Create Admin User') }}</h4>
                <p>{{ _gettext('Everyone will be required to have an account and be logged in at all times to use this website.  This will help protect your site.') }}</p>
                <p>{{ _gettext('You must have at least one administrative account.  Please fill out the information below for the person who will be the administrator of this site.') }}</p>
            </div>
            <form action="{{ route('install.admin') }}" method="post">
                @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <h4 class="alert-heading">{{ _gettext('An error has occurred') }}</h4>
                    <p>{{ _gettext('Please fill out the required fields below.') }}</p>
                </div>
            @endif
                <div class="mb-3 required">
                    <label for="email">{{ _gettext('Email') }}</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
                </div>
                <div class="mb-3 required">
                    <label for="password">{{ _gettext('Password') }}</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="mb-3 required">
                    <label for="password_confirmation">{{ _gettext('Confirm Password') }}</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                </div>
                <div class="mb-3 required">
                    <label for="name">{{ _gettext('Full Name') }}</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                </div>
                <div class="mb-3">
                    <label for="displayname">{{ _gettext('Display Name') }}</label>
                    <input type="text" class="form-control" name="displayname" id="displayname" value="{{ old('displayname') }}">
                    <div class="form-text">{{ _gettext('What do you want to be called on the site?  Leave blank if it is the same as Full Name.') }}</div>
                </div>
                <div class="mb-3 required">
                    <label for="bday">{{ _gettext('Birthday') }}</label>
                    <input type="date" class="form-control" id="bday" name="bday" value="{{ old('bday') }}">
                </div>
                <div class="text-end">
                    <input type="submit" class="btn btn-primary" value="{{ _gettext('Next') }}">
                </div>
            </form>
        </div>
    </main>
</body>
</html>
