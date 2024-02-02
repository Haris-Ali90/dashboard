
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
                    {!! Form::open( ['url' => ['reset-password-finally'], 'files'=> true , 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form']) !!}
                        <h1>Reset your password</h1>
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <fieldset>
                            @if ( $errors->count() )
                                <div class="alert alert-danger">
                                    {!! implode('<br />', $errors->all()) !!}
                                </div>
                            @endif
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus value="{{ $email or old('email') }}">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Confirm password" name="password_confirmation" type="password">
                                </div>

                                <button type="submit" class="btn btn-lg btn-success btn-block">Reset</button>
                        </fieldset>
                    {!! Form::close() !!}
                </section>
            </div>
        </div>
    </div>

@endsection
