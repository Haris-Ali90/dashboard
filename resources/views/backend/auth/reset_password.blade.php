@extends('backend.layouts.app-guest')

@section('title', 'Login')

@section('content')
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
            <div class="row justify-content-center">
                <div class="col-lg-3">
                <img class="dashboard-logo-text" src="{{ url('/') }}/images/abc.png">
                    <form role="form"method="post" action="{{ route('password.email') }}">
                        <h1>Reset your <br> password</h1>
                        {{ csrf_field() }}
                        <input type="hidden" name="role_id"  value="2" />
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
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus value="{{ old('email') }}">
                                </div>

                                <button type="submit" class="btn btn-lg btn-success btn-block">Reset Password</button>
                        </fieldset>
                    </form>
                </div>
            </div>
                </section>
            </div>
        </div>
    </div>

@endsection

