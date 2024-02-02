
@if(can_access_route('warehouseSorter.edit',$userPermissoins))
{{-- <a href="{{ backend_url('warehouse/sorter/edit/'.base64_encode($record->id)) }}" class="btn btn-info btn-xs edit" style="float: left;"><i class="fa fa-pencil">

    </i>  </a> --}}

    <button type="button" onclick="editModal('{{$record->id}}','{{$record->hub_id}}','{{$record->sorting_time}}','{{$record->pickup_time}}','{{$record->delivery_percentage}}')"  class="btn btn-info btn-xs edit" style="float: left;"><i class="fa fa-pencil">
    </i></button>
@endif
{{--
@if(can_access_route('warehouseSorter.delete',$userPermissoins))
{!! Form::model($record, ['method' => 'delete', 'url' => 'warehouse/sorter/'.$record->id, 'class' =>'form-inline form-delete']) !!}
{!! Form::hidden('id', $record->id) !!}
{!! Form::button('<i class="fa fa-trash-o"></i>  ', ['class' => 'btn btn-danger btn-xs', 'name' => 'delete_modal','data-toggle' => 'modal']) !!}
{!! Form::close() !!}
    @endif--}}
