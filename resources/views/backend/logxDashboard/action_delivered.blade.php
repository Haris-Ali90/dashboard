@if(can_access_route('logx-ecommerce-delivered-detail.profile',$userPermissoins))
<a href="{{backend_url('logx/e-commerce/delivered/detail/'.base64_encode($record->sprint_id))}}" title="Detail" target='_blank' class="btn btn-warning btn-xs" style="float: left;">
    <i class="fa fa-folder">
        Details
    </i></a>
@endif