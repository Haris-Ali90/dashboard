@if(can_access_route('newmontreal_notscan.profile',$userPermissoins))
<a href="{{backend_url('newmontreal/notscan/detail/'.base64_encode($record->id))}}" title="Detail" target='_blank' class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif