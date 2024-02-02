@if(can_access_route('walmart-ecommerce-detail.profile',$userPermissoins))
<a href="{{backend_url('walmart/e-commerce/detail/'.$record->sprint_id)}}" title="Detail" target='_blank' class="btn btn-warning btn-xs" style="float: left;">
    <i class="fa fa-folder">
        Details
    </i></a>
    @endif