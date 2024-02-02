@if(can_access_route('ctc-subadmin.profile',$userPermissoins))
<a href="{{backend_url('ctc/subadmin/profile/'.base64_encode($record->id))}}" title="Detail" class="btn btn-primary btn-xs" style="float: left;">
    <i class="fa fa-folder">

    </i>
</a>
@endif
@if(can_access_route('ctc-subadmin.edit',$userPermissoins))
<a href="{{ backend_url('ctc/subadmin/edit/'.base64_encode($record->id)) }}" class="btn btn-info btn-xs edit" style="float: left;"><i class="fa fa-pencil">

    </i>  </a>
@endif
@if(can_access_route('ctc-subadmin.delete',$userPermissoins))
{!! Form::model($record, ['method' => 'delete', 'url' => 'ctc/subadmin/'.$record->id, 'class' =>'form-inline form-delete']) !!}
{!! Form::hidden('id', $record->id) !!}
{!! Form::button('<i class="fa fa-trash-o"></i>  ', ['class' => 'btn btn-danger btn-xs', 'name' => 'delete_modal','data-toggle' => 'modal']) !!}
{!! Form::close() !!}
    @endif