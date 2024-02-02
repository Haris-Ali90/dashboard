@if(can_access_route('newmontreal_delivered.profile',$userPermissoins))
<a href="{{backend_url('newmontreal/delivered/detail/'.base64_encode($record->id))}}" title="Detail" target='_blank' class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif