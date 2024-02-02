
@if(can_access_route('setting.edit',$userPermissoins))
<a href="{{ backend_url('setting/edit/'.base64_encode($record->id)) }}" class="btn btn-info btn-xs edit" style="float: left;"><i class="fa fa-pencil">

    </i>  </a>
@endif
{{--
@if(can_access_route('warehouseSorter.delete',$userPermissoins))
{!! Form::model($record, ['method' => 'delete', 'url' => 'warehouse/sorter/'.$record->id, 'class' =>'form-inline form-delete']) !!}
{!! Form::hidden('id', $record->id) !!}
{!! Form::button('<i class="fa fa-trash-o"></i>  ', ['class' => 'btn btn-danger btn-xs', 'name' => 'delete_modal','data-toggle' => 'modal']) !!}
{!! Form::close() !!}
    @endif--}}
