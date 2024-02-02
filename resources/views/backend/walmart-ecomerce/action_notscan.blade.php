@if(can_access_route('walmart-ecommerce-notscan-detail.profile',$userPermissoins))
<a href="{{backend_url('walmart/e-commerce/notscan/detail/'.base64_encode($record->sprint_id))}}" title="Detail" target='_blank' class="btn btn-warning btn-xs" style="float: left;">
    <i class="fa fa-folder">
        Details
    </i></a>
@endif