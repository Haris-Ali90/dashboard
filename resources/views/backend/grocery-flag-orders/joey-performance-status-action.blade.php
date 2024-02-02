@if(can_access_route('grocery-joey-performance-status.update',$userPermissoins))
    @if($record->is_approved == 0 && $record->unflaged_by == 0)
        <button class="btn btn-info btn-sm performance-status" title="Mark Approved" data-id="{{$record->id}}">
            Mark Approve
        </button> <br>
        <a href="{{ backend_url('un-flag/'.$record->id) }}" class="btn btn-xs btn-danger" >Un Flag Order</a>
    @elseif($record->unflaged_by > 0)
        <span class="label label-success">This Order Un-flag </span>
    @else
        <span class="label label-success">Approved  </span>
    @endif
@endif