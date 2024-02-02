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

              {!! Form::open( ['url' => ['type/auth'], 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form']) !!}

                      <h1>Select Any One</h1>
                      {{ csrf_field() }}
                  <div class='form'>
                      @if(base64_decode($mail) == 1)
                      <input type="radio" id="mail" name="type" value="Mail" required >
                      <label for="Mail" style="margin-left: 8px;
    font-size: 15px;">Authenticate By Mail</label><br>
                      @endif
                       @if(base64_decode($scan) == 1)
                      <input type="radio" id="Scan" name="type" value="Scan" required style="margin-left: 5px">
                      <label for="Scan" style="margin-left: 8px;
    font-size: 15px;">Authenticate By Scan</label><br>
                          @endif
                      <button  id="search" type="submit" class="btn btn-lg btn-success btn-block">Enter</button>

                  </div>
                  <input type="hidden" name='id' value="<?php echo $id ?>"></input>
                  <input type="hidden" name='key' value="<?php echo $key ?>"></input>

              {!! Form::close() !!}
                    </section>
					</div>
					</div>
					</div>
					
@endsection