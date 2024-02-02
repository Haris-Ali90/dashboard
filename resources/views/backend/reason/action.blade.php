@if(can_access_route('reason.edit',$userPermissoins))
<a href="{{ backend_url('reason/edit/'.$record->id) }}" class="btn btn-info btn-xs edit" style="float: left;"><i class="fa fa-pencil"></i> </a>
@endif