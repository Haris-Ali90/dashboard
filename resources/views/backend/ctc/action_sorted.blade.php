@if(can_access_route('ctc_sorted.profile',$userPermissoins))
<a href="{{backend_url('ctc/sorted/detail/'.base64_encode($record->id))}}" title="Detail" class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif