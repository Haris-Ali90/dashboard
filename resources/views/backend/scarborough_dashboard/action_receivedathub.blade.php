@if(can_access_route('scarBorough-receivedathub-detail.profile',$userPermissoins))
<a href="{{backend_url('scarborough/received-at-hub/detail/'.base64_encode($record->ReturnedOrder->sprint_id))}}" title="Detail" target='_blank' class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif