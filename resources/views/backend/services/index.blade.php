@extends( 'backend.layouts.app' )

@section('title', 'Items')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">

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
        $(document).ready(function () {




            $(document).on('click', '.form-delete', function(e){

                var $form = $(this);
                $.confirm({
                    title: 'A secure action',
                    content: 'Are you sure you want to delete item??',
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
            });

        });

    </script>
    <script type="text/javascript">
        <!-- Datatable -->
        $(document).ready(function() {
            $('#datatable').dataTable();
            $(".group1").colorbox({height:"50%",width:"50%"});

        });

    </script>
@endsection

@section('content')


<div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Services</h3>
              </div>

            </div>

            <div class="clearfix"></div>

              {{--@include('backend.layouts.modal')--}}

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Services <small>Items listing</small></h2>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    @include( 'backend.layouts.notification_message' )

                    <table id="datatable" class="table table-striped table-bordered">
                      <thead>
                      <tr>
                        <th>Id</th>
                        <th> Category </th>
                        <th> Name </th>
                        <th> Image </th>
                        <th> Description </th>
                        <th> Price </th>
                        <th>Action</th>
                      </tr>
                      </thead>


                      <tbody>
                      {{--*/ $i = 1 /*--}}
                      @foreach( $services as $record )
                        <tr class="">
                          <td>{{ $i }}</td>
                              <?php $category = App\Category::where('id',$record->category_id)->first(); ?>
                            <td>{{ $category->product_name }}</td>
                            <td>{{ $record->service_name }}</td>
                            <td>{{ $record->service_image }}</td>
                            <td>{{ $record->description }}</td>
                            <td>{{ $record->price }}</td>
                          <td>
{{--
                              <a href="{{ backend_url('subcategories/'.$record->id) }}" class="btn btn-primary btn-xs" style="float: left;"><i class="fa fa-folder"></i> View Sub Categories </a>
--}}

                              <a href="{{ backend_url('service/edit/'.$record->id) }}" class="btn btn-xs btn-primary edit" style="float: left;"><i class="fa fa-pencil"></i></a>

                            {!! Form::model($record, ['method' => 'delete', 'url' => 'backend/service/'.$record->id, 'class' =>'form-inline form-delete']) !!}
                            {!! Form::hidden('id', $record->id) !!}
                            {!! Form::button('<i class="fa fa-trash"></i>', ['class' => 'btn btn-xs btn-danger delete', 'name' => 'delete_modal', 'data-toggle' => 'modal']) !!}
                            {!! Form::close() !!}
                          </td>

                        </tr>
                        {{--*/ $i++ /*--}}
                      @endforeach
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