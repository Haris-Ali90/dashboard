@if(can_access_route('e-commerce.profile',$userPermissoins))
<a href="{{backend_url('e-commerce/detail/'.$record->sprint_id)}}" title=" Details" target='_blank' class="btn btn-warning btn-xs" style="float: left;"> <i class="fa fa-folder">
        Details
    </i>
</a>
@endif
