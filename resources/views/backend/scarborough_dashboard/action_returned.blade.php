@if(can_access_route('scarborough-returned-detail.profile',$userPermissoins))
<a href="{{backend_url('scarborough/returned/detail/'.base64_encode($record->ReturnedOrder->sprint_id))}}" title="Detail" target='_blank' class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif