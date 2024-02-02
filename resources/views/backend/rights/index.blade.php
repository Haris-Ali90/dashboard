@extends( 'backend.layouts.app' )

@section('title', 'Rights')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">

@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Rights
                        <small> You can manage all portals rights and its permissions</small>
                    </h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Admin Roles </h2>
                            <div class="clearfix"></div>
                        </div>
                        
                        {{--@if(session()->has('success'))--}}
                        {{--<div class="alert alert-success">--}}
                        {{--{{ session()->pull('success') }}--}}
                        {{--</div>--}}
                        {{--@endif--}}

                        @include( 'backend.layouts.notification_message' )

                        <div class="x_content">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead stylesheet="color:black;">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Portal Name</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $rights as $record )

                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->display_name }}</td>
                                            <td>
                                                <?php
                                                $portals_name = $portals[$record->type];
                                                $portals_data = $record->GetAttachedPlans;
                                                $total_count = count($portals_data) - 1;

                                                if (!$portals_data->isEmpty()) {
                                                    $portals_name = '';
                                                    foreach ($portals_data->pluck('type')->toArray() as $key => $data) {
                                                        $portals_name .= $portals[$data];
                                                        if ($total_count > $key) {
                                                            $portals_name .= ', ';
                                                        }
                                                    }
                                                }
                                                ?>
                                                {{$portals_name}}
                                            </td>
                                            <td>{{ $record->created_at }}</td>
                                            <td>

                                                @if(can_access_route('right.duplicate',$userPermissoins))
                                                    <a href="javascript:void(0)"
                                                       onclick="duplicateRights({{ $record->id }},'{{ $record->role_name }}','{{ $record->type }}')"
                                                       class="btn btn-primary btn-xs">Duplicate</i>
                                                    </a>
                                                    <!-- Modal -->
                                                @endif
                                                @if(can_access_route('right.show',$userPermissoins))
                                                    <a href="{{ backend_url('right/'.base64_encode($record->id)) }}"
                                                       class="btn btn-primary btn-xs"><i
                                                                class="fa fa-folder">
                                                        </i></a>
                                                @endif
                                                @if(can_access_route('right.edit',$userPermissoins))
                                                    <a href="{{ backend_url('right/'.base64_encode($record->id).'/edit') }}"
                                                       class="btn btn-info btn-xs edit"><i
                                                                class="fa fa-pencil">
                                                        </i></a>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <!-- /#page-wrapper -->
    <div class="modal fade" id="exampleModal" tabindex="-1"
         role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Please Provide new right name </h5>
                    <button type="button" class="close"
                            data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{--     
                <form action="#" method="post">--}}
                    <div class="modal-body">
                        <div class="">
                            <input type="hidden" name="right_id" id="right_id">
                            <input type="hidden" name="role_name" id="role_name">
                            <input type="hidden" name="type" id="type">
                            <label class="control-label">Right Name*</label>
                            <input name="right_name" id="right_name" type="text" value="{{old('right_name')}}"
                                   class="form-control right_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="button" onclick="submitDuplicateRights()" id="duplicate" class="btn btn-primary">Save
                            changes
                        </button>
                    </div>
                    {{--                </form>--}}
            </div>
        </div>
    </div>
@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
@endsection

@section('inlineJS')
   
    <script type="text/javascript">
        <!-- Datatable -->

        $(document).ready(function () 
        {
            $('#datatable').dataTable({
                "order": [[1, "asc"]],
                "lengthMenu": [250, 500, 750, 1000]
            });
            // $(".group1").colorbox({height:"50%",width:"50%"});
        });

        function duplicateRights(id, roleName, type) {

            $('#right_name').val('')
            $('#exampleModal').modal('show')
            $('#right_id').val(id)
            $('#role_name').val(roleName)
            $('#type').val(type)

        }

        function submitDuplicateRights(){

            var rightId = $('#right_id').val();
            var rightName = $('#right_name').val();
            var type = $('#type').val();

            if(rightName == ''){
                alert('Please enter Right Name');
                return false;
            }

            var btn = document.getElementById('duplicate');
            btn.disabled = true;
            btn.innerText = 'Submitting...'

            var roleName = $('#role_name').val();
            // alert(roleName);
            $.ajax({
                url: "right/duplicate",
                type: "POST",
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                data: {'right_id': rightId, 'right_name': rightName, 'role_name': roleName, 'slug_name': roleName, 'type': type},
                success: function (result) {
                    result = JSON.parse(result)
                    if(result.error){
                        alert(result.error)
                        // alert(result[0].error)
                        window.location.href = "right";
                    }
                    if(result.message){
                       window.alert('Duplicate right has been created successfully.');
						window.location.href = "right";
                    }
                },
                error: function (error) {
                    alert(error.responseJSON.right_name[0]);
                    // window.location.href = "right";
                }
            });
        }

    </script>

@endsection