@if(can_access_route('grocery-flag-order.details',$userPermissoins))
    @if($record->is_approved == 1)
<a href="{{ backend_url('grocery/flag-orders-list/details/'.$record->id) }}" class="btn btn-primary btn-xs edit" title="Detail"><i class="fa fa-folder"></i> </a>
    @endif
@endif