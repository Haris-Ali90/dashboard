@if(can_access_route('newottawa_notreturned.profile',$userPermissoins))
<a href="{{backend_url('newottawa/returned-not-hub/detail/'.base64_encode($record->id))}}" target='_blank' title="Detail" class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif