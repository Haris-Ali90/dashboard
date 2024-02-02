@if(can_access_route(['ctc-subadmin.active','ctc-subadmin.inactive'],$userPermissoins))
    @if($record->status === 1)
        <a  class="btn btn-xs" type="button" data-toggle="modal"
            data-target="#statusModal{{ $record->id }}">
            <span class="label label-success">Active</span>
        </a>

        <div id="statusModal{{ $record->id }}" class="modal fade" role="dialog">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                        {!! Form::model($record, ['method' => 'get',  'url' => 'ctc/subadmin/inactive/'.$record->id, 'class' =>'form-inline form-edit']) !!}
                        {!! Form::hidden('id', $record->id) !!}
                        {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    @else
        <a  class="btn btn-xs" type="button" data-toggle="modal"
            data-target="#statusModal{{ $record->id }}">
            <span class="label label-danger">In Active</span>
        </a>


        <div id="statusModal{{ $record->id }}" class="modal fade" role="dialog">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Close</button>
                        {!! Form::model($record, ['method' => 'get',  'url' => 'ctc/subadmin/active/'.$record->id, 'class' =>'form-inline form-edit']) !!}
                        {!! Form::hidden('id', $record->id) !!}
                        {!! Form::submit('Yes', ['class' => 'btn btn-success btn-flat']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

    @endif
@endif
