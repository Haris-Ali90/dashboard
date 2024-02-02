@if(can_access_route('ctc_delivered.profile',$userPermissoins))
<a href="{{backend_url('ctc/delivered/detail/'.base64_encode($record->id))}}" title="Detail" class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif