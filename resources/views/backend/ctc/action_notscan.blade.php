@if(can_access_route('ctc_notscan.profile',$userPermissoins))
<a href="{{backend_url('ctc/notscan/detail/'.base64_encode($record->id))}}" title="Detail" class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif