<?php
$user = Auth::user();
if ($user->email != "admin@gmail.com") {

    $data = explode(',', $user['rights']);
    $permissions = explode(',', $user['permissions']);
} else {
    $data = [];
    $permissions = [];
}
?>

@extends( 'backend.layouts.app' )

@section('title', 'Dashboard')

@section('CSSLibraries')
    <style>
        .dashboard-statistics-box {
            min-height: 400px;
            margin: 15px 0px;
            position: relative;
            box-sizing: border-box;
        }

        .dashboard-statistics-box.dashboard-statistics-tbl-show td {
            padding-top: 52px;
            padding-bottom: 52px;
        }
    </style>
@endsection
@section('JSLibraries')
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ backend_asset('nprogress/nprogress.js') }}"></script>
    <script src="{{ backend_asset('libraries/gauge.js/dist/gauge.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/skycons/skycons.js') }}"></script>
    <script src="{{ backend_asset('libraries/Chart.js/dist/Chart.min.js') }}"></script>

@endsection


@section('content')
    <!--right_col open-->
    <div class="right_col thread_right_wrap" role="main">
        @if (Session::has('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                {!! Session::pull('error') !!}
            </div>
        @endif

        
        <section class="chat_page">
            <div class="row">
                <aside id="left_sidebar" class="col-12 col-lg-4 bc1-lightest-bg">
                    <div class="inner">
                        <div class="widget_sidebar d-none d-lg-block">
                            <div class="widgetTitle_wrap flexbox align-items-center justify-content-between">
                                <h5 class="widgetTitle">Tickets</h5>
                                <div class="form_create_ticket_wrap dd_wrap position-r">
                                    <button class="dd_btn btn btn-white btn-border btn-icon btn-xs"><i class="fa fa-plus"></i></button>
                                    <div class="form_create_ticket_box dd_box">
                                        <form  method="GET" action="" id="ticket_reasons_form" class="needs-validation" novalidate>                                
                                            <div class="form-group no-min-h">
                                                <label for="email">Select the type of issue</label>
                                                <select class="form-control form-control-lg" id="reasonCategoryDD" name="type" required>
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group no-min-h">
                                                <label for="email">Select the reason</label>
                                                <select class="form-control form-control-lg" id="reasonDD" name="type" required>
                                                    
                                                </select>
                                            </div>
                                            <div class="btn-group nomargin">
                                                <button type="submit" class="btn btn-primary submitButton">Create Ticket</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="chat_cat_list_wrap widgetInfo">
                                <div class="thread_type_block">
                                    <h2>Realtime Threads</h2>
                                    <div id="thread_list" class="thread_list">
                                        <div class="row"></div>
                                    </div>
                                </div>
                                <div class="thread_type_block">
                                    <h2>Active Threads</h2>
                                    <div id="active_thread_list" class="thread_list">
                                        <div class="row"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="widget_sidebar d-none d-lg-block">
                            <div class="widgetTitle_wrap flexbox align-items-center justify-content-between">
                                <h5 class="widgetTitle">Groups</h5>
                                <div class="form_create_ticket_wrap dd_wrap position-r">
                                    <button class="dd_btn btn btn-white btn-border btn-icon btn-xs"><i class="fa fa-plus"></i></button>
                                    <div class="form_create_ticket_box dd_box">
                                        <form  method="GET" action="" id="create_group_form" class="needs-validation" novalidate>                                
                                            <div class="form-group no-min-h">
                                                <label for="email">Enter Group Title</label>
                                                <input class="form-control form-control-lg" id="groupName" name="groupName" required/>
                                            </div>
                                            <div class="btn-group nomargin">
                                                <button type="submit" class="btn btn-primary submitButton">Create Group</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="group_list_wrap widgetInfo">
                                <div class="group_list">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
                
                <aside id="right_content" class="chat_page_col col-12 col-lg-8">
                    <div class="chat_inner inner">
                        <div class="chat_header" style="display: none;">
                            <div class="info">
                                <h1 class="h6">
                                    <span class="reason_category f14 dp-block lh-10 regular bf-color"></span>
                                    <span class="reason"></span>
                                </h1>
                            </div>
                        </div>
                        
                        <div class="chat_wrap">
                            <div class="no-result no-thread-selected">
                                <div class="hgroup">
                                    <h4>No Ticket or Group selected</h4>
                                    <p>Please select/create a ticket or group to start chat</p>
                                </div>
                            </div>
                            <div class="chat_list messageArea">
                                <!-- <div class="chat_box incoming">
                                    <div class="inner">
                                        <h4 class="name">Farzan Ahmed</h4>
                                        <div class="message"><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, ipsa.</p></div>
                                    </div>
                                </div>
                                <div class="chat_box outgoing">
                                    <div class="inner">
                                        <h4 class="name">Owais</h4>
                                        <div class="message"><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta, ipsa.</p></div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="chat_textarea">
                                <div class="alert_messages"></div>
                                <div id="chatFiles" class="chat_files"></div>
                                <div class="form-group no-min-h nomargin">
                                    <div class="textMessage_wrap">
                                        <input name="textarea" class="form-control form-control-lg textMessageInput" placeholder="Write a message..."/>
                                        <button id="send_msg_btn" class="send_msg_btn btn btn-primary">Send</button>
                                        <button id="attachFileBtn" class="attach_btn">Attach</button>
                                    </div>
                                </div>
                                <div class="divider center sm"></div>
                                <div class="end_thread align-center">
                                    <a href="#" id="endThreadBtn" class="endThreadBtn btn btn-default">End Chat</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
        <!-- END PAGE CONTENT-->
    </div>


    <!-- footer content -->
    <footer>
        <div class="pull-right">

        </div>
        <div class="clearfix"></div>
    </footer>
    <!-- /footer content -->
    <!-- /#page-wrapper -->
@endsection