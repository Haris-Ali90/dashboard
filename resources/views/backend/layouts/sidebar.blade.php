<?php
    $user = Auth::user();

    if ($user->email != "admin@gmail.com")
    {
        
        $data = explode(',', $user['rights']);
        $dataPermission = explode(',', $user['permissions']);
        
    }
    else
    {
        $data = [];
        $dataPermission = [];
    }

    $userPermissoins = Auth::user()->getPermissions();

?>

<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <div class="title-img-col">
                <a class="site_title" href="{{ backend_url('dashboard') }}">
                    <img class="dashboard-logo-icon" src="{{ url('/') }}/images/logo-no-background.png">
                    <img class="dashboard-logo-text" src="{{ url('/') }}/images/logo-no-background.png">
                </a>
            </div>
        </div>

        <!-- menu profile quick info -->
        <div class="profile">
            <!-- <div class="profile_pic">
                <img src="{{ URL::to('/') }}/images/logo-no-background.png" alt="..." class="img-circle profile_img">
            </div> -->
            <div class="profile_info">
                <!-- <span>Welcome</span>
                <h2>Admin</h2> -->
            </div>
        </div>
        <!-- <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div> -->
        <!-- /menu profile quick info -->

        <br/>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <ul class="nav side-menu">
                {{--<li><a href="{{ backend_url('dashboard') }}"><i class="fa fa-bar-chart"></i> Statistics</a></li>--}}
                    <!-- <li> <a href="{{ backend_url('montreal_dashboard') }}"><i class="fa fa-tachometer"></i> Montreal dashboard</a></li>
                                <li> <a href="{{ backend_url('ottawa_dashboard') }}"><i class="fa fa-tachometer"></i> Ottawa dashboard</a></li> -->

                    <!--<li>-->
                    <!--    <a><i class="fa fa-users"></i>Statistics<span class="fa fa-chevron-down"></span></a>-->
                    <!--    <ul class="nav child_menu">-->
                    <!--        <?php   if(count($data) == 0 || (count($data) > 0 && in_array("statistics", $data))){  ?>-->
                    <!--       {{-- <li><a href="{{ backend_url('dashboard') }}">Dashboard </a></li>--}}-->
                    <!--        <?php } ?>-->
                    <!--        <?php   if(count($data) == 0 || (count($data) > 0 && in_array("montreal_dashboard", $data))){  ?>-->
                    <!--        {{--<li><a href="{{ backend_url('dashboard/montreal') }}">Amazon Montreal </a></li>--}}-->
                    <!--        <?php } ?>-->
                    <!--        <?php   if(count($data) == 0 || (count($data) > 0 && in_array("ottawa_dashboard", $data))){  ?>-->
                    <!--        {{--<li><a href="{{ backend_url('dashboard/ottawa') }}">Amazon Ottawa</a></li>--}}-->
                    <!--        <?php } ?>-->
                    <!--        <?php   if(count($data) == 0 || (count($data) > 0 && in_array("ctc_dashboard", $data))){  ?>-->
                    <!--        <li><a href="{{ backend_url('dashboard/ctc') }}">CTC Dashboard</a></li>-->
                    <!--        <?php } ?>-->
                    <!--        <?php   if(count($data) == 0 || (count($data) > 0 && in_array("walmart_dashboard", $data))){  ?>-->
                            <!-- <li><a href="{{ backend_url('walmart/dashboard') }}">Walmart Dashboard</a></li> -->
                    <!--        <?php } ?>-->
                    <!--    </ul>-->
                    <!--</li>-->

                    <?php
                    
                    if(can_access_route(['borderless-dashboard.index',
                                         'new-borderless-card-dashboard.index',
                                         'borderless-card-dashboard.index',
                                         'new-order-borderless.index',
                                         'new-sort-borderless.index',
                                         'new-pickup-borderless.index',
                                         'new-not-scan-borderless.index',
                                         'new-delivered-borderless.index',
                                         'new-returned-borderless.index',
                                         'new-custom-route-borderless.index',
                                         'borderless_reporting.index',
                                         'borderless-graph.index',
                                         'borderless-route-info.index',
                                         'borderless_reporting.index'],
                                         $userPermissoins))
                    {
                    ?>
                        <li>
                            <a><i class="fa fa-tachometer"></i>Orders <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                               
                                <?php  if(can_access_route(['borderless-dashboard.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto-dashboard') }}"> Active Order</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['new-borderless-card-dashboard.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/card-dashboard') }}"> Statistics</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['new-order-borderless.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/order') }}"> Dashboard</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['new-sort-borderless.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/sorted') }}"> Sorted Order</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['new-pickup-borderless.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/picked/up') }}"> Out For Delivery</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['new-not-scan-borderless.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/not/scan') }}"> Not Scan</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['new-delivered-borderless.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/delivered') }}"> Delivered Orders</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['new-returned-borderless.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/returned') }}">Returned Orders</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['new-custom-route-borderless.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/custom-route') }}"> Create By Custom Route</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['borderless_reporting.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/reporting') }}">Summary</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['borderless-graph.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/graph') }}"> Graph</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['borderless-route-info.index'],$userPermissoins)){  ?>
                                            <li><a href="{{ backend_url('toronto/route-info') }}"> Route Information</a></li>
                                <?php } ?>

                            </ul>
                        </li> 
                     <?php }
                    if(can_access_route(['statistics.index','joey-management.index','brooker-management.index','alert-system.index','statistics-inbound.index','statistics-outbound.index','warehouse-summary.index,manager.index'],$userPermissoins))
                    {
                    ?>
                        <li>
                            <a><i class="fa fa-tasks"></i>Management Portal<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php  if(can_access_route(['statistics.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('statistics') }}"> Management View </a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['joey-management.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('joey-management') }}">Drivers Management</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['brooker-management.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('brooker-management') }}">Brokers Management</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['statistics-inbound.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('inbound') }}"> In Bound</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['statistics-outbound.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('outbound') }}">Out Bound</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['warehouse-summary.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('warehouse/summary') }}">Summary</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['manager.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('manager') }}"> Managers</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['alert-system.index'],$userPermissoins)){  ?>
                                    <li><a href="{{ backend_url('alert-system') }}">Alert System</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php }
                    if(can_access_route(['grocery-flag-order-list.index','grocery-approved-flag-list.index','grocery-un-approved-flag-list.index','grocery-block-joey-flag-list.index'],$userPermissoins))
                    {
                    ?>
                        <li>
                            <a><i class="fa fa-list"></i>Flag Orders<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php  if(can_access_route(['grocery-flag-order-list.index'],$userPermissoins)){  ?>
                                <li> <a href="{{ backend_url('grocery/flag-order-list') }}"> Grocery Flag Order List</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['grocery-approved-flag-list.index'],$userPermissoins)){  ?>
                                <li> <a href="{{ backend_url('grocery/approved-flag-list') }}"> Grocery Approved Flag List</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['grocery-un-approved-flag-list.index'],$userPermissoins)){  ?>
                                <li> <a href="{{ backend_url('grocery/un-approved-flag-list') }}">Un Approved Flag List</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['grocery-block-joey-flag-list.index'],$userPermissoins)){  ?>
                                <li> <a href="{{ backend_url('grocery-block-joey-flag-list') }}"> Blocked Joey List</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php }

                    if(can_access_route(['return-route-info.index'],$userPermissoins))
                    {
                    ?>
                            <li>
                                <a><i class="fa fa-tasks"></i>Returns<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <?php  if(can_access_route(['return-route-info.index'],$userPermissoins)){  ?>
                                    <li><a href="{{ backend_url('return/route-info') }}">Return Routes </a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                    
                    
                    <?php }

                    if(can_access_route(['hub-routific.index','multiple-tracking-id.index','searchorder.index','search-multiple-tracking.index','route.index'],$userPermissoins))
                    {
                    ?>
                        <li>
                            <a><i class="fa fa-list"></i>Order Statuses<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php  if(can_access_route(['multiple-tracking-id.index'],$userPermissoins)){  ?>
                                <li> <a href="{{ backend_url('update/multiple/trackingid') }}"> Update Orders</a></li>
                                <?php } ?>

                                <?php  if(can_access_route(['search-multiple-tracking.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('search/trackingid/multiple') }}"> Search Order</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['manual-status.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('manual/status') }}"> Manual Status History</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['manual-tracking-report.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('manual/tracking/report') }}"> Manual Tracking Report</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['search-tracking.index'],$userPermissoins)){  ?>
                                    <li><a href="{{ backend_url('search/tracking') }}">Tracking</a></li>
                                <?php } ?>
                                <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                                <li><a href="{{ backend_url('manual/route')}}"> Update Route</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    
                    <?php }

                    if(can_access_route(['order-confirmation-list.index','order-confirmation.history','expired-order.history','returned-order.index'],$userPermissoins))
                    {
                    ?>
                        <li>
                            <a><i class="fa fa-list"></i>Customer Support<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php  if(can_access_route(['order-confirmation-list.index'],$userPermissoins)){  ?>
                                <li> <a href="{{ backend_url('order/under-review') }}"> Order Confirmation List</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['order-confirmation.history'],$userPermissoins)){  ?>
                                <li> <a href="{{ backend_url('order/history') }}"> History</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['expired-order.history'],$userPermissoins)){  ?>
                                <li> <a href="{{ backend_url('return/order') }}"> Return To Merchant</a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['returned-order.index'],$userPermissoins)){  ?>
                                <li> <a href="{{ backend_url('returned/order') }}"> Returned Order</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        
                    <?php }

                    if(can_access_route(['reason.index','reason.add'],$userPermissoins))
                    {
                    ?>
                        <li>
                            <a><i class="fa fa-tasks"></i>Reason Settings<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php  if(can_access_route(['reason.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('reason') }}">Reason List </a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['reason.add'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('reason/add') }}"> Add Reason</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        
                        <li>
                            <a><i class="fa fa-tasks"></i>Complain Settings<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                
                                <li><a href="{{ backend_url('complain/register')}}"> Register Complain</a></li>
                            <?php  if(can_access_route(['complain.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('complain') }}">Complain List </a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    
                    <?php }

                    if(can_access_route(['role.index','role.create'],$userPermissoins))
                    {
                    ?>
                        <li>
                            <a><i class="fa fa-tasks"></i>Roles<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <?php  if(can_access_route(['role.index'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('role') }}"> Roles List </a></li>
                                <?php } ?>
                                <?php  if(can_access_route(['role.create'],$userPermissoins)){  ?>
                                <li><a href="{{ backend_url('role/create') }}"> Add Role</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>

                    {{--rights-module-nave--}}
                    @if(can_access_route(['right.index','right.create'],$userPermissoins))
                        <li>
                            <a><i class="fa fa-tasks"></i>Rights<span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                @if(can_access_route(['right.index'],$userPermissoins))
                                <li><a href="{{ backend_url('right') }}"> Rights List </a></li>
                                @endif
                                @if(can_access_route(['right.create'],$userPermissoins))
                                <li><a href="{{ backend_url('right/create') }}"> Add Right</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <?php
                    if(can_access_route(['sub-admin.index','subAdmin.add'],$userPermissoins))
                    {
                    ?>
                    <li>
                        <a><i class="fa fa-users"></i>Sub Admin<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(can_access_route(['sub-admin.index'],$userPermissoins)){  ?>
                            <li><a href="{{ backend_url('subadmins') }}"> Sub Admins </a></li>
                            <?php } ?>
                            <?php  if(can_access_route(['subAdmin.add'],$userPermissoins)){  ?>
                            <li><a href="{{ backend_url('subadmin/add') }}"> Add Sub Admin</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    
                <?php }


                    //if(can_access_route(['dnr.index'],$userPermissoins))
                    //{
                    ?>
                    <!--                    <li>-->
                    <!--    <a><i class="fa fa-list"></i>DNR Reporting<span class="fa fa-chevron-down"></span></a>-->
                    <!--    <ul class="nav child_menu">-->
                    <!--        <?php  if(can_access_route(['dnr.index'],$userPermissoins)){  ?>-->
                    <!--        <li> <a href="{{ backend_url('dnr/reporting') }}">DNR Reporting</a></li>-->
                    <!--        <?php } ?>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                
                
                <?php //}

                    //if(can_access_route(['joey.orders.index'],$userPermissoins))
                    //{
                    ?>
                    <!--<li>-->
                    <!--    <a><i class="fa fa-tasks"></i>Joey Orders<span class="fa fa-chevron-down"></span></a>-->
                    <!--    <ul class="nav child_menu">-->
                    <!--        <?php  if(can_access_route(['joey.orders.index'],$userPermissoins)){  ?>-->
                    <!--        <li><a href="{{ backend_url('joey/orders') }}">Joey Orders </a></li>-->
                    <!--        <?php } ?>-->
                    <!--    </ul>-->
                    <!--</li>-->
                    
                <?php //}
				
                    ?>
                    
                    <?php 
				

                   /* if(can_access_route(['reporting.index'],$userPermissoins))
                    {
                    */?><!--

                    <li>
                        <a><i class="fa fa-file"></i>Vendor Reporting<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php /* if(can_access_route(['reporting.index'],$userPermissoins)){  */?>
                            <li><a href="{{ backend_url('reporting') }}"> Vendor Reporting</a></li>
                            <?php /*} */?>
                        </ul>
                    </li>
                --><?php /*}*/

                   /* if(can_access_route(['ctc_reporting.index'],$userPermissoins))
                    {
                    */?><!--

                    <li>
                        <a><i class="fa fa-file"></i>CTC Reporting<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php /* if(can_access_route(['ctc_reporting.index'],$userPermissoins)){  */?>
                            <li><a href="{{ backend_url('ctcreporting') }}">CTC Reporting</a></li>
                            <?php /*} */?>
                        </ul>
                    </li>
                --><?php /*}*/

                if(count($data) == 0 || (count($data) > 0 && in_array("institute", $data)))
                {
                ?>
                <!--  <li>
                        <a><i class="fa fa-university"></i>Institutes <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li> <a href="{{ backend_url('institutes') }}"> Institutes</a></li>
                        <?php } ?>
                <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("add", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('institute/add') }}"> Add Institute</a></li>
                        <?php } ?>
                        </ul>
                    </li>
 -->
                <?php }
                if(count($data) == 0 || (count($data) > 0 && in_array("teacher", $data)))
                {
                ?>
                <!-- <li>
                        <a><i class="fa fa-child"></i>Donors <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li> <a href="{{ backend_url('donor') }}"> Donors</a></li>
                            <?php } ?>
                <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("add", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('donor/add') }}"> Add Donor</a></li>
                        <?php } ?>
                        </ul>
                    </li> -->

                <?php }

                if(count($data) == 0 || (count($data) > 0 && in_array("student", $data)))
                {
                ?>
                <!--<li>-->
                    <!--    <a><i class="fa fa-child"></i>Service Providers <span class="fa fa-chevron-down"></span></a>-->
                    <!--    <ul class="nav child_menu">-->
                <!--        <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>-->
                <!--        <li> <a href="{{ backend_url('service/providers') }}"> Service Providers</a></li>-->
                <!--        <?php } ?>-->
                <!--        <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("add", $dataPermission))){  ?>-->
                <!--        <li><a href="{{ backend_url('service/providers/add') }}"> Add Service Provider</a></li>-->
                <!--    <?php } ?>-->
                    <!--    </ul>-->
                    <!--</li>-->
                <?php }
                if(count($data) == 0 || (count($data) > 0 && in_array("teachedu", $data)))
                {
                ?>
                <!--  <li>
                        <a><i class="fa fa-building-o"></i>Cities List <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li> <a href="{{ backend_url('cities') }}"> Cities  </a></li>
                        <?php } ?>
                <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("add", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('city/add') }}"> Add City</a></li>
                        <?php } ?>
                        </ul>
                    </li> -->
                <?php }
                if(count($data) == 0 || (count($data) > 0 && in_array("stdedu", $data)))
                {
                ?>
                <!-- <li>
                        <a><i class="fa fa-list"></i> Categories <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li> <a href="{{ backend_url('categories') }}"> Category List  </a></li>
                        <?php } ?>
                <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("add", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('category/add') }}"> Add Category</a></li>
                        <?php  } ?>
                        </ul>
                    </li> -->


                <?php }
                if(count($data) == 0 || (count($data) > 0 && in_array("stdedu", $data)))
                {
                ?>
                <!-- <li>
                        <a><i class="fa fa-list"></i> Zones <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li> <a href="{{ backend_url('zones') }}"> Zone List  </a></li>
                        <?php } ?>
                <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("add", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('zone/add') }}"> Add Zone</a></li>
                        <?php  } ?>
                        </ul>
                    </li> -->

                <?php }
                if(count($data) == 0 || (count($data) > 0 && in_array("stdedu", $data)))
                {
                ?>
                <!-- <li>
                        <a><i class="fa fa-list"></i> Area <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li> <a href="{{ backend_url('area') }}"> Area List  </a></li>
                        <?php } ?>
                <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("add", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('area/add') }}"> Add Area</a></li>
                        <?php  } ?>
                        </ul>
                    </li> -->
                <?php }
                if(count($data) == 0 || (count($data) > 0 && in_array("stdedu", $data)))
                {
                ?>
                <!-- <li>
                        <a><i class="fa fa-list"></i> Tickets <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li> <a href="{{ backend_url('ticket') }}"> Tickets List  </a></li>
                        <?php } ?>
                <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("add", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('ticket/add') }}"> Add Ticket</a></li>
                        <?php  } ?>
                        </ul>
                    </li> -->
                <?php }
                if(count($data) == 0 || (count($data) > 0 && in_array("adv", $data)))
                {
                ?>
                <!-- <li>
                        <a><i class="fa fa-list"></i> Items <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li> <a href="{{ backend_url('services') }}"> Items List  </a></li>
                        <?php } ?>
                <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("add", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('service/add') }}"> Add Item</a></li>
                        <?php  } ?>
                        </ul>
                    </li> -->
                <?php }
                if(count($data) == 0 || (count($data) > 0 && in_array("content", $data)))
                {
                ?>
                <!--  <li>
                        <a><i class="fa fa-book"></i>Content<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('cms') }}"> View Content</a></li>
                        </ul>
                    </li>  -->
                <?php }
                if(count($data) == 0 || (count($data) > 0 && in_array("contact", $data)))
                {
                ?>
                <!-- <li>
                        <a><i class="fa fa-book"></i>Contact Us<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('contactus') }}">Contact Us List</a></li>
                        <?php } ?> -->
                <!-- <li> <a href="{{ backend_url('contactus') }}"><i class="fa fa-phone-square"></i> Contact Us</a></li>
 -->
                    <!-- </ul>
                </li> -->
                <?php
                }
                if(count($data) == 0 || (count($data) > 0 && in_array("noti", $data)))
                {
                ?>
                <!-- <li>
                        <a><i class="fa fa-bell"></i>Notifications<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('notifications') }}">Notification List</a></li>
                        <?php } ?>

                        </ul>
                    </li> -->
                <?php
                }
                if(count($data) == 0 || (count($data) > 0 && in_array("message", $data)))
                {
                ?>
                <!-- <li>
                        <a><i class="fa fa-envelope"></i>Message<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data) == 0 || (count($dataPermission) > 0 && in_array("read", $dataPermission))){  ?>
                        <li><a href="{{ backend_url('message/add') }}">Message Add</a></li>
                        <?php }?>
                        <li> <a href="{{ backend_url('contactus') }}"><i class="fa fa-phone-square"></i> Contact Us</a></li>

                        </ul>
                    </li>  -->
                <?php
                }

                ?>
                <!-- <li>
                        <a><i class="fa fa-stethoscope"></i>Medical Staff <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li> <a href="{{ backend_url('staffs') }}"> Medical Staff</a></li>
                            <li><a href="{{ backend_url('staff/add') }}"> Add Staff</a></li>
                        </ul>
                    </li> -->


                <!-- <li>
                        <a><i class="fa fa-list"></i>Categories <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('categories') }}"> View Categories</a></li>
                            <li><a href="{{ backend_url('categories/add') }}"> Add Category</a></li>
                        </ul>
                    </li>

                    <li>
                        <a><i class="fa fa-list"></i> Speciality <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('subcategories') }}"> View Speciality</a></li>
                            <li><a href="{{ backend_url('subcategories/add') }}"> Add Speciality</a></li>
                        </ul>
                    </li>

                    <li>
                        <a><i class="fa fa-list"></i> Sub Speciality <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('products') }}"> View Sub Speciality</a></li>
                            <li><a href="{{ backend_url('products/add') }}">Add Sub Speciality</a></li>
                        </ul>
                    </li> -->
                <!--   <li>
                        <a><i class="fa fa-hospital-o"></i>Hospitals <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li> <a href="{{ backend_url('hospitals') }}"> Hospitals  </a></li>
                            <li><a href="{{ backend_url('hospital/add') }}"> Add Hospitals</a></li>
                        </ul>
                    </li> -->
                <!-- <li>
                        <a><i class="fa fa-book"></i>Content<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('cms') }}"> View Content</a></li>
                        </ul>
                    </li> -->


                </ul>
            </div>

        </div>
        <!-- /sidebar menu -->

    </div>
</div>

<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
           


            <ul class="nav navbar-nav navbar-right">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
        </div>
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        <img src="{{$user->profile_picture}}" alt="">{{$user->full_name}}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <!-- <li><a href="{{ backend_url('logout') }}"><i class="fa fa-sign-out pull-right"></i> Logout</a></li> -->
                                @if (count($data)== 0)
                                <li><a href="{{ backend_url('adminedit/'.base64_encode(auth()->user()->id)) }}"><i class="fa fa-edit pull-right"></i>Edit Profile</a>
                                </li>
                                @endif
								@if(can_access_route('account-security.edit',$userPermissoins))
                                <li>
                                     <a href="{{ backend_url('account/security/edit/'.base64_encode(auth()->user()->id)) }}"><i class="fa fa-lock pull-right"></i>Account Security
                                    </a>
                                </li>
                        @endif
                                @if(can_access_route('sub-admin-change.password',$userPermissoins))
                                <li>
                                    <a href="{{ backend_url('changepwd') }}"><i class="fa fa-key pull-right"></i>
                                    Change Password
                                    </a>
                                </li>
                                @endif
                        <li>
                            <a href="#" onclick="document.getElementById('logout-form').submit();"><i
                                        class="fa fa-sign-out pull-right"></i> Logout</a>
                            <form id="logout-form" action="{{ url('logout') }}" method="POST">
                                {{ csrf_field() }}
                            </form>
                        </li>

                    </ul>
                </li>


            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->

<script type="text/javascript">

    // var ajax_call = function() {


    // };
    // var interval = 1000 * 60 * X; // where X is your every X minutes

    // setInterval(ajax_call, interval);


</script>