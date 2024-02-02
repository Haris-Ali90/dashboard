@extends( 'backend.layouts.app' ) @section('title', 'Un Approved Grocery Flag
List') @section('CSSLibraries')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css"
    integrity="sha256-b5ZKCi55IX+24Jqn638cP/q3Nb2nlx+MH/vMMqrId6k=" crossorigin="anonymous" />
<!-- DataTables CSS -->
<link href="{{
    backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css')
  }}" rel="stylesheet" />
<!-- DataTables Responsive CSS -->
<link href="{{
    backend_asset('libraries/datatables-responsive/dataTables.responsive.css')
  }}" rel="stylesheet" />
<!-- Image Viewer CSS -->
<link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet" />
<!-- Custom Light Box Css -->
<link href="{{ backend_asset('css/custom_lightbox.css') }}" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-submit-btn {
        margin-top: 26px;
    }

    .select2-container--default .select2-selection--multiple {
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
        -webkit-transition: border-color ease-in-out 0.15s,
            -webkit-box-shadow ease-in-out 0.15s;
        -o-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
        transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    }

    .label-success {
        background-color: #5cb85c;
    }

    .label-success[href]:hover,
    .label-success[href]:focus {
        background-color: #449d44;
    }

    button.dt-button.buttons-csv.buttons-html5 {
        visibility: hidden;
    }

    #showValue {
        background-color: #5cb85c;
    }
</style>
@endsection @section('JSLibraries')
<!-- DataTables JavaScript -->
<script src="{{
    backend_asset('libraries/datatables/js/jquery.dataTables.min.js')
  }}"></script>
<script src="{{
    backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js')
  }}"></script>
<script src="{{
    backend_asset('libraries/datatables-responsive/dataTables.responsive.js')
  }}"></script>
<script src="{{
    backend_asset('libraries/galleria/jquery.colorbox.js')
  }}"></script>
<!-- Custom Theme JavaScript -->
<script src="{{ backend_asset('js/sweetalert2.all.min.js') }}"></script>
<!-- Custom Light Box JS -->
<script src="{{ backend_asset('js/custom_lightbox.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"
    integrity="sha256-5YmaxAwMjIpMrVlK84Y/+NjCpKnFYa8bWWBbUHSBGfU=" crossorigin="anonymous"></script>
@endsection @section('inlineJS')

<script type="text/javascript">
    <!-- Datatable -->

    $(document).ready(function () {

        $('#datatable').dataTable({
            "lengthMenu": [250, 500, 750, 1000]
        });
        $(".group1").colorbox({ height: "50%", width: "50%" });

        $("#start_date").datetimepicker
            ({
                format: 'YYYY-MM-DD',
                maxDate: '{{date('Y- m - d')}}',
      });

    $("#end_date").datetimepicker({
        format: 'YYYY-MM-DD',
        maxDate: '{{date('Y- m - d')}}',
      });

    $("#start_date").on("dp.change", function (e) {
        $('#end_date').data("DateTimePicker").minDate(e.date);
    });

    $("#end_date").on("dp.change", function (e) {
        $('#start_date').data("DateTimePicker").maxDate(e.date);
    });

    $(document).on('click', '.form-delete', function (e) {

        var $form = $(this);
        $.confirm({
            title: 'A secure action',
            content: 'Are you sure you want to delete reason ??',
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

    $('#birthday').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_4"
    }, function (start, end, label) {
        console.log(start.toISOString(), end.toISOString(), label);
    });




});

    $(document).on('click', '.performance-status', function (e) {
        let el = $(this);
        var id = el.attr("data-id");

        $.confirm({
            title: 'A secure action',
            content: 'Are you sure you want to mark approved?',
            icon: 'fa fa-question-circle',
            animation: 'scale',
            closeAnimation: 'scale',
            opacity: 0.5,
            buttons: {
                'confirm': {
                    text: 'Proceed',
                    btnClass: 'btn-info',
                    action: function () {
                        showLoader();
                        $.ajax({
                            type: "GET",
                            url: "{{URL::to('/')}}/grocery/joey-flag/performance/" + id,
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
    // dwonload btn clcik genete file name
    function getExportFileName() {
        // setting up filter date
        var filter_start_date = ($('input[name="start_date"]').val() == "") ? filter_date : $('input[name="start_date"]').val();
        var filter_start_end = ($('input[name="end_date"]').val() == "") ? filter_date : $('input[name="end_date"]').val();
        return 'Un Approved Grocery Flag List ' + filter_start_date + " to " + filter_start_end;

    };
    // bind datatable csv download btn to external buton
    $('.datatable-csv-download-btn').click(function () {
        $('.buttons-csv').trigger('click');
    });
    $DataTable = $('.flag-orders-table').DataTable({
        order: [[0, 'desc']],
        searching: true,
        select: false,
        pageLength: 250,
        autoWidth: false,
        scrollX: true, // enables horizontal scrolling,
        scrollCollapse: true,
        fixedColumns: true,
        dom: 'Bflrtip',
        buttons: [/*{
extend: 'excel',
text: 'Excel',
filename:"_excel"

},*/
            {
                extend: 'csv',
                text: 'CSV Export',
                filename: function () { return getExportFileName(); },
            }],
        //lengthMenu: [ 10, 25, 50, 75, 100 ],
        serverSide: true,
        processing: true,
        ajax: {
            url: '{{ route('grocery-un-approved-flag-list.data') }}',
        data: function (data) {
            data.start_date = jQuery('[name=start_date]').val();
            data.end_date = jQuery('[name=end_date]').val();
            data.joeys = jQuery(".joeys-list").val();
        },
    },
        columns: [
        { data: 'all', orderable: false, searchable: false, className: 'text-center' },
        { data: 'id', orderable: true, searchable: true, className: 'text-center' },
        { data: 'merchant_order_num', orderable: true, searchable: true, className: 'text-center' },
        { data: 'sprint_id', orderable: false, searchable: false, className: 'text-center' },
        { data: 'flag_cat_name', orderable: false, searchable: false, className: 'text-center' },
        { data: 'portal_type', orderable: false, searchable: false, className: 'text-center' },
        { data: 'joey_name', orderable: false, searchable: false, className: 'text-center' },
        { data: 'flag_by', orderable: false, searchable: false, className: 'text-center' },
        { data: 'created_at', orderable: false, searchable: false, className: 'text-center' },
        { data: 'attachment_path', orderable: false, searchable: false, className: 'text-center' },
        { data: 'joey_performance_status', orderable: false, searchable: false, className: 'text-center' },
        { data: 'action', orderable: false, searchable: false, className: 'text-center' },

    ]
});

    $('.form-submit-btn').click(function () {
        $DataTable.ajax.reload();
        return false;
    });
</script>

@endsection @section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <!-- <h3>Un Approved Grocery Flag Orders<small></small></h3> -->
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
                            <h2>Un Approved Grocery Flag Orders List</h2>
                        </div>
                        <div class="" style="float: right">
                            <button class="datatable-csv-download-btn c-btn"  type="button">
                                Export CSV
                            </button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <form class="form-horizontal table-top-form-from">
                        <!--table-top-form-row-open-->
                        <div class="row table-top-form-row">
                            <!--table-top-form-col-warp-open-->
                            <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                <!-- control-label
control-label
control-label -->
                                <div class="form-group">
                                    <label class="">From Date</label>
                                    <input id="start_date" name="start_date" max="{{ date('Y-m-d') }}" value="{{
                      isset($_GET['fromdatepicker'])
                        ? $_GET['fromdatepicker']
                        : date('Y-m-d')
                    }}" type="text" class="form-control" />
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-3 table-top-form-col-warp">
                                <div class="form-group">
                                    <label class="">To Date</label>
                                    <input id="end_date" name="end_date" max="{{ date('Y-m-d') }}" value="{{
                      isset($_GET['todatepicker'])
                        ? $_GET['todatepicker']
                        : date('Y-m-d')
                    }}" type="text" class="form-control" />
                                </div>
                                {{--<input name="search_date" value="@if($old_request_data){{
                    trim($old_request_data['search_date'])
                  }}@endif" type="date" class="form-control" />--}}
                            </div>

                            <div class="col-sm-3 col-md-3 model-input-col">
                                <div class="form-group">
                                    <label class="">Drivers list</label>
                                    <select class="form-control joeys-list" name="joeys[]" multiple>
                                        @foreach($all_joeys_accept_selected as $joey)
                                        <option value="{{$joey->id}}">
                                            {{$joey->full_name}} {{$joey->id}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!--table-top-form-col-warp-open-->
                            <div class="col-sm-3 col-md-2 table-top-form-col-warp">
                                <button class="btn orange form-submit-btn c-btn" type="button">
                                    Filter
                                </button>
                            </div>
                            <!--table-top-form-col-warp-close-->
                        </div>
                        <!--table-top-form-row-close-->
                    </form>
                    <div class="x_content">
                        @include( 'backend.layouts.notification_message' )
                        @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                        @endif
                        @if(can_access_route('multiple.approved.flag.grocery',$userPermissoins))
                        <button type="submit"
                            class="col-md-1 btn btn-success btn-sm multiple-approved-flag approved-for-multiple-flag"
                            id="showValue" data-object="{}" data-id="" data-tracking_id="" style="display: none">
                            Marked Approved
                        </button>
                        @endif

                        <table class="table table-striped table-bordered flag-orders-table">
                            <thead>
                                <tr>
                                    <th>
                                        Select All
                                        <input type="checkbox" name="tracking-ids[]" class="select-all" />
                                    </th>
                                    <th>ID</th>
                                    <th>Merchant Order No</th>
                                    <th>Order NO</th>
                                    <th>Flag Category Names</th>
                                    <th>Flag From</th>
                                    <th>Driver Name</th>
                                    <th>Flag By</th>
                                    <th>Flagged Date</th>
                                    <th>POD</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /#page-wrapper -->

@endsection @section('multi-script')
<script>
    $(document).ready(function () {
        $(".joeys-list").select2({
            minimumInputLength: 2,
            placeholder: "Search a joey to assign",
            allowClear: true,
            matcher: matchStart,
            sorter: function (data) {
                return data.sort(function (a, b) {
                    return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
                });
            },
        });

        // search by start characters
        function matchStart(params, data) {
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === "") {
                return data;
            }

            // Do not display the item if there is no 'text' property
            if (typeof data.text === "undefined") {
                return null;
            }

            // checking the search is by id or name
            if (isNaN(parseInt(params.term))) {
                // block for string search
                if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
                    return $.extend({}, data, true);
                }
            } else {
                // number search block
                if (data.text.indexOf(params.term) > -1) {
                    return (modifiedData = $.extend({}, data, true));
                }
            }
            // matching start characters

            // return null
            return null;
        }
    });

    $(".select-all").click(function () {
        let is_checked = $(this).prop("checked");
        if (is_checked) {
            $(".flagCheckbox").prop("checked", true); // Checks it
        } else {
            $(".flagCheckbox").prop("checked", false); // Un-checks it
        }
        loadCheck(is_checked);
    });

    $(document).on("click", ".flagCheckbox", function (e) {
        single_checkbox_fn($(this), "single");
    });

    // single checkbox fucntion
    function single_checkbox_fn(el, type = "load") {
        let element = el;
        let tracking_id = element.val();
        let data_flag_id = element.attr("data-flag");
        let btn_data = JSON.parse(
            $(".approved-for-multiple-flag").attr("data-object")
        );
        if (element.prop("checked") == true) {
            btn_data[data_flag_id] = {
                tracking_id: tracking_id,
                flag_id: data_flag_id,
            };
        } else {
            // remove uncheck all checked btn
            if (type == "single") {
                $(".select-all").prop("checked", false);
            }

            delete btn_data[data_flag_id];
        }

        // checking the object is empty
        if (type == "single" && Object.keys(btn_data).length === 0) {
            $(".approved-for-multiple-flag").hide();
        } else if (type == "single" && Object.keys(btn_data).length > 0) {
            $(".approved-for-multiple-flag").show();
        }
        // updated
        btn_data = JSON.stringify(btn_data);
        $(".approved-for-multiple-flag").attr("data-object", btn_data);
    }

    //Function for get and set value for reattempt
    function loadCheck(type) {
        // getting all check box
        var all_checkbox_count = $(".flagCheckbox");
        // getting checked boxes
        var flagCheckbox_checked = $('input[name="flag-approved"]:checked');
        // get button
        let button = $(".multiple-approved-flag");

        //checking the total box checked then checek all show
        if (flagCheckbox_checked.length < all_checkbox_count.length) {
            $(".select-all").prop("checked", false);
        } else {
            $(".select-all").prop("checked", true);
        }

        // checking all checked type
        if (type) {
            // looping up the checked boxes for set values
            flagCheckbox_checked.each(function (index) {
                single_checkbox_fn($(this));
            });
            // show button
            button.show();
        } else {
            // make checked box to unchecked
            all_checkbox_count.prop("checked", false);

            // remove data from button
            button.attr("data-object", "{}");
            // hide button
            button.hide();
        }
    }

    function selectAll(source) {
        checkboxes = document.getElementsByName("foo");
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    //Multiple Flag Approved Order
    $(document).on("click", ".multiple-approved-flag", function (e) {
        let el = $(this);
        var data_object = JSON.parse(el.attr("data-object"));

        $.confirm({
            title: "A secure action",
            content: "Are you sure you want to multiple package sort orders?",
            icon: "fa fa-question-circle",
            animation: "scale",
            closeAnimation: "scale",
            opacity: 0.5,
            buttons: {
                confirm: {
                    text: "Proceed",
                    btnClass: "btn-info",
                    action: function () {
                        showLoader();
                        $.ajax({
                            type: "GET",
                            url: "{{URL::to('/')}}/multiple/approved/flag/grocery",
                            data: {
                                data: data_object,
                            },
                            // success: function (res) {
                            //     hideLoader();
                            //     var data = JSON.parse(res)
                            //     ShowSessionAlert('success', data.message);
                            //     setTimeout(function(){
                            //         location.reload();
                            //     }, 1000);
                            // },
                            // error: function (error) {
                            //     hideLoader();
                            //     ShowSessionAlert('danger', 'Something critical went wrong');
                            //     console.log(error);
                            // }
                            success: function (res) {
                                hideLoader();
                                // checking responce
                                if (res.status == false) {
                                    ShowSessionAlert("danger", res.message);
                                    return false;
                                }
                                let button = $(".multiple-approved-flag");
                                button.hide();
                                ShowSessionAlert("success", res.message);
                                $DataTable.row($(el).parents("tr")).remove().draw();
                            },
                            error: function (error) {
                                hideLoader();
                                console.log(error);
                                ShowSessionAlert("danger", "Something critical went wrong");
                            },
                        });
                    },
                },
                cancel: function () {
                    //$.alert('you clicked on <strong>cancel</strong>');
                },
            },
        });
    });
</script>
@endsection