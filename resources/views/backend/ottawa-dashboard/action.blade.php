
@if(can_access_route('ottawa-order.profile',$userPermissoins))
<a href="{{backend_url('ottawa-dashboard/order/detail/'.$record->task_id)}}" title=" Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;"> <i class="fa fa-folder">
        Details
    </i>
</a>
@endif
