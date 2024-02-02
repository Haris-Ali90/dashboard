@if(can_access_route('newottawa_notscan.profile',$userPermissoins))
<a href="{{backend_url('newottawa/notscan/detail/'.base64_encode($record->id))}}" target='_blank' title="Detail" class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif