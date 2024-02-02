
@if(can_access_route('subAdmin.profile',$userPermissoins))

    <a href="{{backend_url('sub/admin/profile/'.base64_encode($record->id))}}" title="Detail" class="btn sub-ad btn-primary btn-xs" style="float: left;">
        <i class="fa fa-folder">

        </i>
    </a>
@endif
@if(can_access_route('subAdmin.edit',$userPermissoins))
    <a href="{{ backend_url('subadmin/edit/'.base64_encode($record->id)) }}" class="btn btn-info sub-ad btn-xs edit" style="float: left;">
        <i class="fa fa-pencil">
        </i>  
    </a>
@endif

@if(can_access_route('subAdmin.delete',$userPermissoins))
    {!! Form::model($record, ['method' => 'delete', 'url' => 'sub/admin/'.$record->id, 'class' =>'form-inline form-delete']) !!}
    {!! Form::hidden('id', $record->id) !!}
    {!! Form::button('<i class="fa fa-trash-o"></i>  ', ['class' => ' sub-ad danger btn-xs', 'name' => 'delete_modal','data-toggle' => 'modal']) !!}
    {!! Form::close() !!}
@endif