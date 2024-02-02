@extends( 'backend.layouts.app' )

@section('title', 'Block Joeys Flag List')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <!-- Custom Light Box Css -->
    <link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>

    .form-submit-btn{
        margin-top: 26px;
    }
    .select2-container--default .select2-selection--multiple
    {
        min-height: 34px;
        padding: 1px 10px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 0px !important;
        -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
        box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
        -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    }
</style>
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


            $(document).on('click', '.unblock-joey', function (e) {
                let el = $(this);
                var joey_id = el.attr("data-id");

                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to unblock this joey?',
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    closeAnimation: 'scale',
                    opacity: 0.5,
                    buttons: {
                        'confirm': {
                            text: 'Proceed',
                            btnClass: 'btn-info',
                            action: function () {
                                var id = joey_id;
                                showLoader();
                                $.ajax({
                                    type: "GET",
                                    url: "{{URL::to('/')}}/block-joey-flag/unblock/" + id,
                                    success: function (res) {
                                        hideLoader();
                                        // checking responce
                                        if (res.status == false) {
                                            ShowSessionAlert('danger', res.message);
                                            return false;
                                        }

                                        ShowSessionAlert('success', res.message);
                                        $DataTable.row($(el).parents('tr'))
                                            .remove()
                                            .draw();


                                    },
                                    error: function (error) {
                                        hideLoader();
                                        console.log(error);
                                        ShowSessionAlert('danger', 'Something critical went wrong');
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



        });

        $DataTable = $('.flag-orders-table').DataTable({
            order: [[ 0, 'desc']],
            searching: true,
            select: false,
            pageLength: 250,
            autoWidth: false,
            //lengthMenu: [ 10, 25, 50, 75, 100 ],
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('block-joey-flag-list.data') }}',
                data: function(data) {

                },
            },
            columns: [
                {data: 'id',   orderable: true,   searchable: true ,className:'text-center'},
                {data: 'joey_id',   orderable: true,   searchable: true, className:'text-center'},
                {data: 'joey_name',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'joey_email',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'joey_phone',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'suspension_date',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'incident_value',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'created_at',   orderable: false,   searchable: false,className:'text-center'},
                {data: 'action',   orderable: false,   searchable: false,className:'text-center'},
            ]
        });
    </script>


@endsection

@section('content')


    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left">
                    <h3>Block Joeys Flag List<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Blocked Joey List</h2>

                            <div class="clearfix"></div>
                        </div>
                        {{--<form class="form-horizontal table-top-form-from">
                            <!--table-top-form-row-open-->
                            <div class="row table-top-form-row">
                                <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">From Date</label>
                                    <input name="start_date" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" type="date" class="form-control">
                                </div>
                                <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                    <label class="control-label">To Date</label>
                                    <input name="end_date" max="{{date('Y-m-d')}}" value="{{date('Y-m-d')}}" type="date" class="form-control">
                                    --}}{{--<input name="search_date" value="@if($old_request_data){{trim($old_request_data['search_date'])}}@endif"  type="date" class="form-control">--}}{{--
                                </div>

                                <div class="col-sm-3 col-md-3 model-input-col">
                                    <label class="control-label">Joeys list *</label>
                                    <select class="form-control joeys-list"  name="joeys[]" multiple>
                                        @foreach($all_joeys_accept_selected as $joey)
                                            <option value="{{$joey->id}}">{{$joey->full_name}} {{$joey->id}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--table-top-form-col-warp-open-->
                                <div class="col-sm-3 col-md-2 table-top-form-col-warp">
                                    <button class="btn orange form-submit-btn"  type="button"> Filter </button>
                                </div>
                                <!--table-top-form-col-warp-close-->

                            </div>
                            <!--table-top-form-row-close-->
                        </form>--}}
                        <div class="x_content">

                            @include( 'backend.layouts.notification_message' )

                            @if(session()->has('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif


                            <table class="table table-striped table-bordered flag-orders-table" >
                                <thead>
                                <tr>
                                    <th style="width: 5%">ID</th>
                                    <th>Joey ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Suspension Duration</th>
                                    <th>Applied Incident Value</th>
                                    <th>Block Date</th>
                                    <th>Unblock</th>
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
    <!-- /#page-wrapper -->

@endsection
@section('multi-script')
<script>
    $(document).ready(function () {

    });
</script>
@endsection
