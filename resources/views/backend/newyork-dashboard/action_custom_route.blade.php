@if(can_access_route('newyork-CustomRoute-detail.profile',$userPermissoins))
<a href="{{backend_url('newyork-dashboard/custom-route/detail/'.base64_encode($record->task_id))}}" title="Detail" target='_blank' class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i></a>
@endif