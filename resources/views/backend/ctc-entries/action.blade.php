{{--@if(can_access_route('new-ctc-order.profile',$userPermissoins))--}}
    {{--<a href="{{backend_url('new/ctc/order/detail/'.$record->sprint_id)}}" title=" Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;"> <i class="fa fa-folder">--}}
            {{--Details--}}
        {{--</i>--}}
    {{--</a>--}}


{{--@endif--}}
<a href="{{backend_url('ctc/new/detail/'.$record->sprint_id)}}" title=" Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;"> <i class="fa fa-folder">
        Details
    </i>
</a>
