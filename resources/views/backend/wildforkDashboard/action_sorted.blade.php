@if(can_access_route('wildfork-ecommerce-sorted-detail.profile',$userPermissoins))
<a href="{{backend_url('wildfork/e-commerce/sorted/detail/'.base64_encode($record->sprint_id))}}" title="Detail" target='_blank' class="btn btn-warning btn-xs" style="float: left;">
    <i class="fa fa-folder">
        Details
    </i></a>
@endif