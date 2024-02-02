@extends('backend.layouts.app-guest')

@section('title', 'Login')

@section('content')

<div>
    <div id="main" class="page-login">
        <div class="pg-container container-fluid" >
            <div class="row_1 row align-items-top no-gutters justify-content-end">
                <aside class="left-column col-12 col-md-5 full-h d-none d-sm-block">
                        <a class="hiddenanchor" id="signup"></a>
                        <a class="hiddenanchor" id="signin"></a>
                </aside>
                <aside class="right-column col-12 col-sm-7">
                        <div class="login_wrapper">
                            <div class="animate form login_form">
                                <div class="inner full-h-min flexbox flex-center">
                                    <div class="full-w">
                                        <div id="logo" class="dp-table marginauto mb-20">
                                            <img class="dashboard-logo-text" src="{{ url('/') }}/images/logo-no-background.png">
                                        </div>
                                        <div class="row no-gutters justify-content-center">
                                            <div class="col-10 col-md-9 col-lg-5 col-xl-5">
                                                <div class="hgroup divider-after align-center">
                                                    <h1>Al Rafeeq Reporting Portal</h1>
                                                    <p class="f14">To login please enter your login credentials</p>
                                                </div>
                                                <form role="form" method="POST">
                                                    
                                                    {{ csrf_field() }}
                                                    <fieldset>
                                                        @if (session('status'))
                                                        <div class="alert alert-success" style="font-size: 15px">
                                                            {{ session('status') }}
                                                        </div>
                                                        @endif
                                                        @if ( $errors->count() )
                                                        <div class="alert alert-danger" style="font-size: 15px">
                                                            {!! implode('<br />', $errors->all()) !!}
                                                        </div>
                                                        @endif
                                                        <div class="form-group">
                                                            <label for="emailInput">Email / Username</label>
                                                            <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus
                                                                value="{{ old('email') }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="paswordInput">Password</label>
                                                            <input class="form-control" placeholder="Password" name="password" type="password" value=""
                                                                required>
                                                        </div>

                                                        <div class="text-center mt-10 ">
                                                            <button type="submit" class="btn btn-primary submitButton">Login</button>
                                                        </div>

                                                        <div class="text-center  c-margin">
                                                            <a href="{{ backend_url('reset-password') }}">Forgot password?</a>
                                                        </div>
                                                    </fieldset>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </aside>
            </div>
        </div>
    </div>
</div>

@endsection 