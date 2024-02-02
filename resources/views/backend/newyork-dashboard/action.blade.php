@if(can_access_route('newyork-order.profile',$userPermissoins))
<a href="{{backend_url('newyork-dashboard/order/detail/'.$record->task_id)}}" title=" Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;"> <i class="fa fa-folder">
        Details
    </i>
</a>
@endif
