@extends('backend.layouts.app-guest')

@section('title', 'Login')

@section('content')
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <img class="dashboard-logo-text" src="{{ url('/') }}/images/abc.png">
                    <form method="POST" action="{{ route('reset.password.update') }}" aria-label="Reset Password">
                        <h1>Reset your password</h1>
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token ?$token: '' }}">

                        <input type="hidden" name="role_id" value="{{ $role_id ?$role_id: '' }}">

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ( $errors->count() )
                            <div class="alert alert-danger">
                                {!! implode('<br />', $errors->all()) !!}
                            </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-md-12">

                                <input id="email" type="email" placeholder="E-mail" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?$email: old('email') }}" readonly>

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">

                                <input id="password" type="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">

                                <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg btn-success btn-block">Reset Password</button>
                    </form>
                </section>
            </div>
        </div>
    </div>

@endsection

