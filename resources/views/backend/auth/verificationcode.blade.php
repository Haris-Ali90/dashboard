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

              <h3>Please enter the 6-digit verification code we sent via Email:</h3>
              <span>(we want to make sure it's you before we contact our movers)</span>
              <br>
              <br>
              <div id="form">

                      {!! Form::open( ['url' => ['verify/code'], 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form']) !!}

                  @if ( $errors->count() )
                      <div class="alert alert-danger">
                          {!! implode('<br />', $errors->all()) !!}
                      </div>
                  @endif
                      <input   type="text" name='code' required  class="form-control" placeholder="Enter Code..."></input>


                      <input type="hidden" name='email' value="<?php echo $email ?>"></input>

                      <input type="hidden" name='key' value="<?php echo $key ?>"></input>

                      <button type ='submit' class="btn btn-lg btn-success btn-block">Verify</button>
                  {!! Form::close() !!}
              </div>
                    </section>
					</div>
					</div>
					</div>
					
@endsection