@if(can_access_route(['sub-admin.active','sub-admin.inactive'],$userPermissoins))
    @if($record->status === 1)
        <a  class="" type="button" data-toggle="modal"
            data-target="#statusModal{{ $record->status }}">
            <span class="label label-success">Active</span>
        </a>

        <div id="statusModal{{ $record->status }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirm Status?</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to change the status?</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                        {!! Form::model($record, ['method' => 'get',  'url' => 'sub-admin/inactive/'.$record->id, 'class' =>'form-inline form-edit']) !!}
                        {!! Form::hidden('id', $record->id) !!}
                        {!! Form::submit('Yes', ['class' => 'btn btn-success c-btn btn-flat']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    @else
        <a  class="" type="button" data-toggle="modal"
            data-target="#statusModal{{ $record->status }}">
            <span class="label label-danger">In Active</span>
        </a>


        <div id="statusModal{{ $record->status }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Confirm Status?</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to change the status?</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-default btn-flat close pull-left" data-dismiss="modal">Close</button>
                        {!! Form::model($record, ['method' => 'get',  'url' => 'sub-admin/active/'.$record->id, 'class' =>'form-inline form-edit']) !!}
                        {!! Form::hidden('id', $record->id) !!}
                        {!! Form::submit('Yes', ['class' => 'btn btn-success c-btn btn-flat']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    @endif
@endif
