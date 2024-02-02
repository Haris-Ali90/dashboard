@if(can_access_route('newottawa_CustomRoute.profile',$userPermissoins))
<a href="{{backend_url('newottawa/custom-route/detail/'.base64_encode($record->id))}}" target='_blank' title="Detail" class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif