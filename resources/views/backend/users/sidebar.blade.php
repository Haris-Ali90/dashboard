<?php  
$user = Auth::user();
if($user->email!="admin@gmail.com")
{

$data = explode(',', $user['rights']);
$dataPermission = explode(',', $user['permissions']);
}

else{
    $data = [];
    $dataPermission=[];
}
 ?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">

            <a class="site_title" href="{{ backend_url('dashboard') }}"> <img src="{{ URL::to('/') }}/public/images/default.png" width="50px" height="50px" > Jugadoo  </a>
        </div>

        <div class="clearf/div>

        <!-- menu profile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="{{ URL::to('/') }}/public/images/default.png" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>Admin</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                            <li> <a href="{{ backend_url('dashboard') }}"><i class="fa fa-tachometer"></i> Dashboard</a></li>
                             <?php     
                              if(count($data)==0){
                             ?>
                     <!-- <li>
                        <a><i class="fa fa-users"></i>Sub Admins  <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li> <a href="{{ backend_url('subadmins') }}"> Sub Admins </a></li>
                            <li><a href="{{ backend_url('subadmin/add') }}"> Add Sub Admin</a></li>
                        </ul>
                    </li> -->
                <?php }
                 if(count($data)== 0 || (count($data)>0 && in_array("institute", $data)))
                 {
                 ?>
                   <!--  <li>
                        <a><i class="fa fa-university"></i>Institutes <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                            <li> <a href="{{ backend_url('institutes') }}"> Institutes</a></li>
                        <?php } ?>
                        <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("add", $dataPermission))){  ?>
                            <li><a href="{{ backend_url('institute/add') }}"> Add Institute</a></li>
                        <?php } ?>
                        </ul>
                    </li>
 -->
                    <?php }
                 if(count($data)== 0 || (count($data)>0 &&in_array("teacher", $data)))
                 {
                 ?>
                   <!--  <li>
                        <a><i class="fa fa-user"></i>Teachers <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                            <li> <a href="{{ backend_url('teachers') }}"> Teachers</a></li>
                        <?php } ?>
                        <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("add", $dataPermission))){  ?>
                            <li><a href="{{ backend_url('teacher/add') }}"> Add Teacher</a></li>
                        <?php } ?>
                        </ul>
                    </li> -->
                    <?php }
                 if(count($data)== 0 || (count($data)>0 &&in_array("student", $data)))
                 {
                 ?>
                    <li>
                        <a><i class="fa fa-child"></i>Users <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                            <li> <a href="{{ backend_url('users') }}"> Users</a></li>
                            <?php } ?>
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("add", $dataPermission))){  ?>
                            <li><a href="{{ backend_url('user/add') }}"> Add User</a></li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php }
                 if(count($data)== 0 || (count($data)>0 &&in_array("teachedu", $data)))
                 {
                 ?>
                    <li>
                        <a><i class="fa fa-building-o"></i>Cities List <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                            <li> <a href="{{ backend_url('cities') }}"> Cities  </a></li>
                        <?php } ?>
                        <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("add", $dataPermission))){  ?>
                            <li><a href="{{ backend_url('city/add') }}"> Add City</a></li>
                        <?php } ?>
                        </ul>
                    </li>
                    <?php }
                 if(count($data)== 0 || (count($data)>0 &&in_array("stdedu", $data)))
                 {
                 ?>
                    <!--  <li>
                        <a><i class="fa fa-graduation-cap"></i>Student Education <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                            <li> <a href="{{ backend_url('student/degree') }}"> Student Educations  </a></li>
                        <?php } ?>
                        <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("add", $dataPermission))){  ?>
                            <li><a href="{{ backend_url('student/degree/add') }}"> Add Student Edcation</a></li>
                        <?php  } ?>
                        </ul>
                    </li> -->
                    <?php }
                 if(count($data)== 0 || (count($data)>0 &&in_array("adv", $data)))
                 {
                 ?>
                <!--     <li>
                        <a><i class="fa fa-file-image-o"></i>Advertise Image <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                            <li> <a href="{{ backend_url('advertise') }}"> Advertise Image  </a></li>
                        <?php } ?>
                           
                        </ul>
                    </li> -->
                    <?php }
                 if(count($data)== 0 || (count($data)>0 &&in_array("content", $data)))
                 {
                 ?>
                   <!--  <li>
                        <a><i class="fa fa-book"></i>Content<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ backend_url('cms') }}"> View Content</a></li>
                        </ul>
                    </li>  -->
                    <?php }
                 if(count($data)== 0 || (count($data)>0 &&in_array("contact", $data)))
                 {
                 ?>
                    <li>
                        <a><i class="fa fa-book"></i>Contact Us<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                            <li><a href="{{ backend_url('contactus') }}">Contact Us List</a></li>
                        <?php } ?>
                            <!-- <li> <a href="{{ backend_url('contactus') }}"><i class="fa fa-phone-square"></i> Contact Us</a></li>
 -->
                        </ul>
                    </li> 
                    <?php
                     }
                     if(count($data)== 0 || (count($data)>0 &&in_array("noti", $data)))
                 {
                 ?>
                    <li>
                        <a><i class="fa fa-bell"></i>Notifications<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                            <li><a href="{{ backend_url('notifications') }}">Notification List</a></li>
                        <?php } ?>
                            <!-- <li> <a href="{{ backend_url('contactus') }}"><i class="fa fa-phone-square"></i> Contact Us</a></li>
 -->
                        </ul>
                    </li> 
                    <?php
                     }        
                 if(count($data)== 0 || (count($data)>0 &&in_array("message", $data)))
                 {
                 ?>
                    <!-- <li>
                        <a><i class="fa fa-envelope"></i>Message<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php  if(count($data)== 0 || (count($dataPermission)>0 && in_array("read", $dataPermission))){  ?>
                            <li><a href="{{ backend_url('message/add') }}">Message Add</a></li>
                        <?php }?>
                            <!-- <li> <a href="{{ backend_url('contactus') }}"><i class="fa fa-phone-square"></i> Contact Us</a></li>
 -->
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

                    <!--  -->
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
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="../public/images/profile_6.jpg" alt="">admin
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">

                                    <li><a href="{{ backend_url('logout') }}"><i class="fa fa-sign-out pull-right"></i> Logout</a></li>
                                    <li><a href="{{ backend_url('changepwd') }}"><i class="fa fa-key pull-right"></i>Change Password</a></li>
                                </ul>
                            </li>


                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

<script type="text/javascript">
    
var ajax_call = function() {


    
  
};
var interval = 1000 * 60 * X; // where X is your every X minutes

setInterval(ajax_call, interval);





</script>