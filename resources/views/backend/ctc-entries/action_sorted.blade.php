@if(can_access_route('new-ctc-sorted-detail.profile',$userPermissoins))
<a href="{{backend_url('new/ctc/sorted/detail/'.base64_encode($record->sprint_id))}}" title="Detail" target='_blank' class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif