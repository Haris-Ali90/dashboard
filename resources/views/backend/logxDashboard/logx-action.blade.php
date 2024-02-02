@if(can_access_route('logx-detail.profile',$userPermissoins))
<a href="{{backend_url('logx/detail/'.$record->sprint_id)}}"title=" Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;">
    <i class="fa fa-folder">
        Details
    </i></a>
    @endif