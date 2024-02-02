@extends( 'backend.layouts.app' )

@section('title', 'Alert System Setting')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">

@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{ backend_asset('js/sweetalert2.all.min.js') }}"></script>
    <!-- Custom Light Box JS -->
    <script src="{{ backend_asset('js/custom_lightbox.js')}}"></script>
@endsection

@section('inlineJS')
    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function () {

            $('#datatable').dataTable({
                "lengthMenu": [ 250, 500, 750, 1000 ]
            });
            $(".group1").colorbox({height:"50%",width:"50%"});

            $(document).on('click', '.status_change', function(e){

                var Uid = $(this).data('id');

                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to change sub admin status??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {

                                $.ajax({
                                    type: "GET",
                                    data: {},
                                    success: function(data)
                                    {
                                        if(data== '0' || data== 0 )
                                        {
                                            var DataToset	=	'<button type="button" class="btn btn-warning btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Blocked</button>';
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
                                        else
                                        {
                                            var DataToset	=	'<button type="button" class="btn btn-success btn-xs status_change" data-toggle="modal" data-id="'+Uid+'" data-target=".bs-example-modal-sm">Active</button>'
                                            $('#CurerntStatusDiv'+Uid).html(DataToset);
                                        }
                                    }
                                });

                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });

            $(document).on('click', '.form-delete', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to delete Alert System Setting ??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info ',
                            action: function () {
                                $form.submit();
                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });


            /*$(document).on('click', '.status_change', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to change sub admin status??',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {
                                $form.submit();
                            }
                        },
                        cancel: function () {
                            //$.alert('you clicked on <strong>cancel</strong>');
                        }
                    }
                });
            });*/


        });

    </script>
    <script>
        $(document).ready(function() {
            $('#birthday').daterangepicker({
                singleDatePicker: true,
                calender_style: "picker_4"
            }, function(start, end, label) {
                console.log(start.toISOString(), end.toISOString(), label);
            });
        });
    </script>

    <script>

        $(function () {
            appConfig.set('yajrabox.ajax', '{{ route('warehousesorter.data') }}');
            appConfig.set('dt.order', [0, 'desc']);
            appConfig.set('yajrabox.scrollx_responsive', true);
            appConfig.set('yajrabox.autoWidth', false);
            appConfig.set('yajrabox.ajax.data', function (data) {
                data.hub_id = jQuery('[name=hub_id]').val();
                data.month_id= jQuery('[name=month_id]').val();
                
            });

            appConfig.set('yajrabox.columns', [
                {data: 'id',   orderable: true,   searchable: true ,className: 'text-center'},
                {data: 'hub_name', orderable: false,   searchable: false},
                // {data: 'date',   orderable: true,   searchable: true,className: 'text-center'},
                // {data: 'internal_sorter_count',   orderable: false,   searchable: true,className: 'text-center'},
                // {data: 'brooker_sorter_count',   orderable: false,   searchable: true,className: 'text-center'},
                // {data: 'dispensed_route',   orderable: false,   searchable: true,className: 'text-center'},
                {data: 'sorting_time',   orderable: false,   searchable: true,className: 'text-center'},
                {data: 'pickup_time',   orderable: false,   searchable: true,className: 'text-center'},
                // {data: 'manager_on_duty',   orderable: false,   searchable: true,className: 'text-center'},
                {data: 'delivery_percentage',   orderable: false,   searchable: false,className: 'text-center'},
                {data: 'action',   orderable: false,   searchable: false,className: 'text-center '},
            ]);
        })

      
        $("#insertForm .submitbtn").click(function(e){
            e.preventDefault();
            var hub=$('#insertForm #hub_id').val();
            var sort=$('#insertForm #sorting_time').val();
            var pick=$('#insertForm #pickup_time').val();
            var delivery=$('#insertForm #delivery_percentage').val();

            if(hub=="" || sort=='' || pick=="" || delivery==''){
                alert('All fields are required.');
            }
            else{   
                $.ajax({
                    type:'POST',
                    url:"{{ route('check-for-hub') }}",
                    data:{hub_id:hub,_token: "{{ csrf_token() }}",},
                    success:function(data){
                        console.log(data.status);
                        if(data.status==200){
                            if (confirm('This hub is already saved.Are you sure you want to update?')) {
                                // alert('Thanks for confirming');
                                var url="{{route('warehousesorter.update',1)}}";
                                url= url.slice(0, -1)+""+data.id;
                                $('#insertForm').attr('action', url);
                                $("<input>").attr({
                                    name: "_method",
                                    type: "hidden",
                                    value: 'PUT'
                                }).appendTo("#insertForm");
                                $( "#insertForm" ).submit();
                            } else {
                                // alert('Why did you press cancel? You should have confirmed');
                            }

                        }
                        else{
                            // alert('Insert.');
                            $( "#insertForm" ).submit();
                        }
                    }
                });
            }
        });
        function editModal(id,hub_id,sorting,pick,delivery){
            $('#myeditModal').modal('show');
            var url="{{route('warehousesorter.update',0)}}";
            url= url.slice(0, -1)+""+id;
            $('#myeditModal #sorting_time').val(sorting);
            $('#myeditModal #pickup_time').val(pick);
            $('#myeditModal #delivery_percentage').val(delivery);

            $('#myeditModal #hub_id option[value="'+hub_id+'"]').prop('selected', true);
            $('#myeditModal form').attr('action', url);

        }
    </script>
@endsection

@section('content')
<style>
.select2-container {
    width: 100% !important;
}
</style>

    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <!-- <h3>Alert System Setting <small></small></h3> -->
                </div>
            </div>
            
            <div class="clearfix"></div>

            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
            
                    <div class="x_panel">
                        <div class="x_title">
                          <div class="col-lg-6">
                          <h2>Alert System Setting List <small></small></h2>
                          </div>
                           <div class="col-lg-6">
                           <button type="button" class="btn c-btn btn-primary pull-right" data-toggle="modal" data-target="#myModal" >Add</button>
                           </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_title">
                            <form method="get"action="">
                                <div>
                                    <div class="col-md-3">
                                       <div class="form-group">
                                       <label> Select Hub</label>
                                        <select class="form-control" name="hub_id" required>
                                            <option value=""> Select Hub</option>
                                            @foreach( $hubs as $hub )
                                            @if(isset($_GET['hub_id']) && $hub->id==$_GET['hub_id'])
                                                <option selected value="{{ $hub->id }}"> {{ $hub->city_name }}</option>
                                                @else
                                                <option  value="{{ $hub->id }}"> {{ $hub->city_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                       </div>
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <label>Select Month</label>
                                        <select class="form-control" name="month_id" required>
                                            <option value=""> Select Month</option>
                                            @if(isset($_GET['month_id']))
                                            <option {{ $_GET['month_id']==='01' ? 'Selected' : '' }} value="01" >Janaury</option>
                                            <option {{ $_GET['month_id']==='02' ? 'Selected' : '' }} value='02'>February</option>
                                            <option {{ $_GET['month_id']==='03' ? 'Selected' : '' }} value='03'>March</option>
                                            <option {{ $_GET['month_id']==='04' ? 'Selected' : '' }} value='04'>April</option>
                                            <option  {{ $_GET['month_id']==='05' ? 'Selected' : '' }} value='05'>May</option>
                                            <option {{ $_GET['month_id']==='06' ? 'Selected' : '' }} value='06'>June</option>
                                            <option {{ $_GET['month_id']==='07' ? 'Selected' : '' }} value='07'>July</option>
                                            <option {{ $_GET['month_id']==='08' ? 'Selected' : '' }} value='08'>August</option>
                                            <option {{ $_GET['month_id']==='09' ? 'Selected' : '' }} value='09'>September</option>
                                            <option {{ $_GET['month_id']==='10' ? 'Selected' : '' }} value='10'>October</option>
                                            <option {{ $_GET['month_id']==='11' ? 'Selected' : '' }} value='11'>November</option>
                                            <option {{ $_GET['month_id']==='12' ? 'Selected' : '' }} value='12'>December</option>
                                                @else
                                                <option  value="01" >Janaury</option>
                                            <option  value='02'>February</option>
                                            <option value='03'>March</option>
                                            <option  value='04'>April</option>
                                            <option   value='05'>May</option>
                                            <option  value='06'>June</option>
                                            <option  value='07'>July</option>
                                            <option  value='08'>August</option>
                                            <option value='09'>September</option>
                                            <option  value='10'>October</option>
                                            <option value='11'>November</option>
                                            <option value='12'>December</option>
                                                @endif
                                           
                                        </select>
                                    </div> --}}

                                    <div class="col-md-9 d-flex justify-content-end">
                                        <button class="btn c-btn btn-primary" type="submit" style="margin-top: 25px;">
                                            Go
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                        

                             @include( 'backend.layouts.notification_message' )

                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif


                            <table class="table table-striped table-bordered yajrabox" >
                                <thead>
                                <tr>
	                      			<th style="width: 5%;text-align: center">ID</th>
                                    <th style="width: 25%">Hub</th>
                                    {{-- <th style="width: 10%;text-align: center">Date </th>
                                    <th style="width: 10%;text-align: center">Internal Sorter Counts</th>
                                    <th style="width: 10%;text-align: center">Brooker Sorter Counts</th>
                                    <th style="width: 8%;text-align: center">Dispensed Route</th> --}}
                                    <th style="width: 8%;text-align: center">Sorting Hours</th>
                                    <th style="width: 8%;text-align: center">Pickup Hours</th>
                                    {{-- <th style="width: 25%;text-align: center">Manager On Duty</th> --}}
                                    <th style="width: 25%;text-align: center">Delivery Percentage</th>
                                    <th style="width: 5%;text-align: center">Action</th>
	                      		    </tr>
                                </thead>


                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Add Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add</h4>
        </div>
        <div class="modal-body">
            {!! Form::open( ['url' => ['warehouse/sorter/create'], 'files'=> true , 'method' => 'POST', 'class' => 'form-horizontal form-label-left', 'role' => 'form' ,'id'=>"insertForm"]) !!}
                @include( 'backend.warehousesorter.form' )
            {!! Form::close() !!}
        </div>    
      </div>
  
    </div>
</div>
<div id="myeditModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit</h4>
        </div>
        <div class="modal-body">
            {!! Form::open( ['url' => ['warehouse/sorter/update',0], 'method' => 'PUT','files' => true , 'role' => 'form']) !!}
                <div class="form-group{{ $errors->has('role_type') ? ' has-error' : '' }}">
                    {{ Form::label('hub_id', 'Hub  ', ['class'=>'']) }}
                        {{-- <div class="col-md-6 col-sm-6 col-xs-12"> --}}
                        <select readonly class="form-control" name="hub_id" id="hub_id" required="">
                            @foreach( $hubs as $record )
                            @if($record->id==old('hub_id'))
                                {
                                    <option disabled selected value="{{ $record->id }}"> {{ $record->city_name }}</option>
                                }
                                @else
                                <option disabled value="{{ $record->id }}"> {{ $record->city_name }}</option>
                                @endif
                            @endforeach
                        </select>
                    {{-- </div> --}}
                    @if ( $errors->has('hub_id') )
                        <p class="help-block">{{ $errors->first('hub_id') }}</p>
                    @endif
                </div>
                
                <div class="form-group{{ $errors->has('sorting_time') ? ' has-error' : '' }}">
                    {{ Form::label('sorting_time', 'Sorting Hours', ['class'=>'']) }}
                    {{-- <div class="col-md-6 col-sm-6 col-xs-12"> --}}
                        {{ Form::Number('sorting_time', old('sorting_time'), ['class' => 'form-control', 'required'=>true,'onkeyup'=>"if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"]) }}
                    {{-- </div> --}}
                    @if ( $errors->has('sorting_time') )
                        <p class="help-block">{{ $errors->first('sorting_time') }}</p>
                    @endif
                </div>
                
                <div class="form-group{{ $errors->has('pickup_time') ? ' has-error' : '' }}">
                    {{ Form::label('pickup_time', 'Pickup Hours', ['class'=>'']) }}
                    {{-- <div class="col-md-6 col-sm-6 col-xs-12"> --}}
                        {{ Form::Number('pickup_time', old('pickup_time'), ['class' => 'form-control', 'required'=>true,'onkeyup'=>"if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"]) }}
                    {{-- </div> --}}
                    @if ( $errors->has('pickup_time') )
                        <p class="help-block">{{ $errors->first('pickup_time') }}</p>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('delivery_percentage') ? ' has-error' : '' }}">
                    {{ Form::label('delivery_percentage', 'Delivery Percentage ', ['class'=>'control-label ']) }}
                    {{-- <div class="col-md-6 col-sm-6 col-xs-12"> --}}
                        {{ Form::Number('delivery_percentage', old('delivery_percentage'), ['class' => 'form-control' , 'required'=>true,'onkeyup'=>"if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"]) }}
                    {{-- </div> --}}
                    @if ( $errors->has('delivery_percentage') )
                        <p class="help-block">{{ $errors->first('delivery_percentage') }}</p>
                    @endif
                </div>
                
                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="d-flex justify-content-end">
                         {{ Html::link( backend_url('alert-system'), 'Cancel', ['class' => 'btn sub-ad c-close btn-default']) }}
                        {{ Form::submit('Save', ['class' => 'btn sub-ad btn-primary']) }}
                       
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
      </div>
  
    </div>
</div>
    <!-- /#page-wrapper -->

@endsection