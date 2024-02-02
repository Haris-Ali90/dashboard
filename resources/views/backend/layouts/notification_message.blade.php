<div class="session-wrapper">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if( Session::has('alert-' . $msg) )
            <div class="alert alert-{{ $msg }} alert-dismissable">
                <button type="button" class="close " data-dismiss="alert" aria-hidden="true">×</button>
                {{ Session::get('alert-' . $msg) }}
            </div>
        @endif
    @endforeach
    @if (isset($errors) && count($errors) > 0)
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger custom ">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                {{ $error }}<br/>
            </div>
        @endforeach
    @endif
</div>