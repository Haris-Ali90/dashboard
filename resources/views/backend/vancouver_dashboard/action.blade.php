
@if(can_access_route('vancouver-order.profile',$userPermissoins))
<a href="{{backend_url('vancouver/order/detail/'.$record->task_id)}}" title=" Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;"> <i class="fa fa-folder">
        Details
    </i>
</a>
@endif
