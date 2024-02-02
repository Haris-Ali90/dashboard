<div class="row" id="ottawaCards">

    <!--top_tiles Div Open-->
    <div class="top_tiles montreal-dashbord-tiles" id="ottawa-dashboard-tiles-id">

    @if(in_array($type, ['all','total']))
        <!--Animated-a Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">

            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->
            <a href="<?php echo URL::to('/'); ?>/newottawa?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=total">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="count" id="total_orders">0</div>{{--{{ isset($amazon_count)?$amazon_count['total']:0 }}</div>--}}
                <h3>Total Orders</h3>
            </div>
            </a>
        </div>
        <!--Animated-a Div Close-->
    @endif

    {{--@if(in_array($type, ['all','manifest']))
        <!--Animated-b Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  mainfest-order show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->

            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-truck"></i>
                </div>
                <div class="count" id="mainfest_orders">0</div>--}}{{--{{ $mainfest_orders }}</div>--}}{{--
                <h3> Amazon Orders</h3>
            </div>
        </div>
        <!--Animated-b Div Close-->
    @endif--}}

    @if(in_array($type, ['all','return']))
                <!--Animated-c Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->
            <a href="<?php echo URL::to('/'); ?>/newottawa/returned?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=return">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-rotate-left"></i>
                </div>
                <div class="count"c id="return_orders">0</div>{{--{{ isset($amazon_count)?$amazon_count['return_orders']:0 }}</div>--}}
                <h3> Return Orders</h3>
            </div>
            </a>
        </div>
                @if(in_array($type, ['return']))
                    <!--Animated-c Div Open-->
                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
                        <!--dashbords-conts-tiles-loader-main-wrap-open-->
                        <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                            <div class="dashbords-conts-tiles-loader-inner-wrap">
                                <div class="lds-roller">
                                    <div class="dot-1"></div>
                                    <div class="dot-2"></div>
                                    <div class="dot-3"></div>
                                    <div class="dot-4"></div>
                                    <div class="dot-5"></div>
                                    <div class="dot-6"></div>
                                    <div class="dot-7"></div>
                                    <div class="dot-8"></div>
                                </div>
                            </div>
                        </div>
                        <!--dashbords-conts-tiles-loader-main-wrap-close-->
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-rotate-left"></i>
                            </div>
                            <div class="count" id="hub_return_scan">0
                            </div>{{--{{  isset($amazon_count)?$amazon_count['return_orders']:0 }}</div>--}}
                            <h3>Returns Received At Hub</h3>
                        </div>
                    </div>

                    <!--Animated-c Div Open-->
                    <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
                        <!--dashbords-conts-tiles-loader-main-wrap-open-->
                        <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                            <div class="dashbords-conts-tiles-loader-inner-wrap">
                                <div class="lds-roller">
                                    <div class="dot-1"></div>
                                    <div class="dot-2"></div>
                                    <div class="dot-3"></div>
                                    <div class="dot-4"></div>
                                    <div class="dot-5"></div>
                                    <div class="dot-6"></div>
                                    <div class="dot-7"></div>
                                    <div class="dot-8"></div>
                                </div>
                            </div>
                        </div>
                        <!--dashbords-conts-tiles-loader-main-wrap-close-->
                        <a href="<?php echo URL::to('/'); ?>/newottawa/returned-not-hub?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=return">
                        <div class="tile-stats">
                            <div class="icon">
                                <i class="fa fa-rotate-left"></i>
                            </div>
                            <div class="count" id="hub_not_return_scan">0
                            </div>{{--{{  isset($amazon_count)?$amazon_count['return_orders']:0 }}</div>--}}
                            <h3>Returns Not Received At Hub</h3>
                        </div>
                        </a>
                    </div>
                    @endif
        @endif

        @if(in_array($type, ['all','yesterday']))
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  yesterday-order show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->

            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-rotate-left"></i>
                </div>
                <div class="count" id="yesterday_orders">0</div>{{--{{ $yesterday_return_orders }}</div>--}}
                <h3> Yesterday Returns Orders</h3>
            </div>
        </div>
        <!--Animated-c Div Close-->
    @endif

    @if(in_array($type, ['all','sorted']))
        <!--Animated-d Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->
            <a href="<?php echo URL::to('/'); ?>/newottawa/sorted?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=sorted">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-sort-amount-asc"></i>
                </div>
                <div class="count" id="sorted_orders">0</div>{{--{{ isset($amazon_count)?$amazon_count['sorted']:0 }}</div>--}}
                <h3> Total Sorted Orders</h3>
            </div>
            </a>
        </div>
        <!--Animated-d Div Close-->
    @endif

    @if(in_array($type, ['all','picked']))
        <!--Animated-e Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->
            <a href="<?php echo URL::to('/'); ?>/newottawa/picked/up?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=picked">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-car"></i>
                </div>
                <div class="count" id="picked_orders">0</div>{{--{{ isset($amazon_count)?$amazon_count['pickup']:0 }}</div>--}}
                <h3> Out For Delivery Orders</h3>
            </div>
            </a>
        </div>
        <!--Animated-e Div Close-->
    @endif

    @if(in_array($type, ['all','delivered']))
        <!--Animated-f Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->
            <a href="<?php echo URL::to('/'); ?>/newottawa/delivered?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=delivered">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="count" id="delivered_orders">0</div>{{--{{ isset($amazon_count)?$amazon_count['delivered_order']:0 }}</div>--}}
                <h3> Total Delivered Orders</h3>
            </div>
            </a>
        </div>
        <!--Animated-f Div Close-->
    @endif

    @if(in_array($type, ['all','scan']))
        <!--Animated-g Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->
            <a href="<?php echo URL::to('/'); ?>/newottawa/not/scan?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=scan">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-calendar"></i>
                </div>
                <div class="count" id="notscan_orders">0</div>{{--{{ isset($amazon_count)?$amazon_count['notscan']:0 }}</div>--}}
                <h3>Scheduled At Hub</h3>
            </div>
            </a>
        </div>
        <!--Animated-g Div Close-->
            <!--Animated-g Div Open-->
            <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
                <!--dashbords-conts-tiles-loader-main-wrap-open-->
                <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                    <div class="dashbords-conts-tiles-loader-inner-wrap">
                        <div class="lds-roller">
                            <div class="dot-1"></div>
                            <div class="dot-2"></div>
                            <div class="dot-3"></div>
                            <div class="dot-4"></div>
                            <div class="dot-5"></div>
                            <div class="dot-6"></div>
                            <div class="dot-7"></div>
                            <div class="dot-8"></div>
                        </div>
                    </div>
                </div>
                <!--dashbords-conts-tiles-loader-main-wrap-close-->
                <a href="<?php echo URL::to('/'); ?>/newottawa/not/scan?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=scan">
                    <div class="tile-stats">
                        <div class="icon">
                            <i class="fa fa-repeat"></i>
                        </div>
                        <div class="count" id="reattempted_orders">0</div>{{--{{ isset($amazon_count)?$amazon_count['notscan']:0 }}</div>--}}
                        <h3>Reattempted At Hub</h3>
                    </div>
                </a>
            </div>
            <!--Animated-g Div Close-->
    @endif

    @if(in_array($type, ['all','failed']))
        <!--Animated-h Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  failed-order show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->

            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="count" id="failed_orders">0</div>{{--{{ $failed_orders }}</div>--}}
                <h3> Failed Orders</h3>
            </div>
        </div>
        <!--Animated-h Div Close-->

    @endif

    @if(in_array($type, ['all','custom']))
        <!--Animated-h Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
            <!--dashbords-conts-tiles-loader-main-wrap-open-->
            <div class="dashbords-conts-tiles-loader-main-wrap  custom-route show">
                <div class="dashbords-conts-tiles-loader-inner-wrap">
                    <div class="lds-roller">
                        <div class="dot-1"></div>
                        <div class="dot-2"></div>
                        <div class="dot-3"></div>
                        <div class="dot-4"></div>
                        <div class="dot-5"></div>
                        <div class="dot-6"></div>
                        <div class="dot-7"></div>
                        <div class="dot-8"></div>
                    </div>
                </div>
            </div>
            <!--dashbords-conts-tiles-loader-main-wrap-close-->
            <a href="<?php echo URL::to('/'); ?>/newottawa/custom-route?datepicker=<?php echo isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d');?>&type=custom">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="count" id="custom_orders">0</div>{{--{{  isset($amazon_count)?$amazon_count['custom_route']:0 }}</div>--}}
                <h3> Create By Custom Route</h3>
            </div>
            </a>
        </div>
        <!--Animated-h Div Close-->
        @endif

    </div>
    <!--top_tiles Div Close-->

</div>

<hr>

<div class="row dashbords-conts-tiles-main-wrap" id="ottawaCards">

    <div class="top_tiles montreal-dashbord-tiles" id="ottawa-dashbord-tiles-id">
        @if(in_array($type, ['all']))
            <h2>Current Stats</h2>
        @endif

    @if(in_array($type, ['all']))
        <!--Animated-a Div Open-->

            <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
                <!--dashbords-conts-tiles-loader-main-wrap-open-->
                <div class="dashbords-conts-tiles-loader-main-wrap  total-summary show">
                    <div class="dashbords-conts-tiles-loader-inner-wrap">
                        <div class="lds-roller">
                            <div class="dot-1"></div>
                            <div class="dot-2"></div>
                            <div class="dot-3"></div>
                            <div class="dot-4"></div>
                            <div class="dot-5"></div>
                            <div class="dot-6"></div>
                            <div class="dot-7"></div>
                            <div class="dot-8"></div>
                        </div>
                    </div>
                </div>
                <div class="tile-stats">
                    <div class="icon">
                        <i class="fa fa-sort-amount-asc"></i>
                    </div>
                    <div class="count" id="sorted_remain">0
                    </div>{{--{{ isset($amazon_count)?$amazon_count['total']:0 }}</div>--}}
                    <h3>Remaining To Be Picked Up</h3>
                </div>
            </div>

            <!--Animated-a Div Close-->
    @endif

    @if(in_array($type, ['all']))
        <!--Animated-b Div Open-->
            <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
                <!--dashbords-conts-tiles-loader-main-wrap-open-->
                <div class="dashbords-conts-tiles-loader-main-wrap  total-summary show">
                    <div class="dashbords-conts-tiles-loader-inner-wrap">
                        <div class="lds-roller">
                            <div class="dot-1"></div>
                            <div class="dot-2"></div>
                            <div class="dot-3"></div>
                            <div class="dot-4"></div>
                            <div class="dot-5"></div>
                            <div class="dot-6"></div>
                            <div class="dot-7"></div>
                            <div class="dot-8"></div>
                        </div>
                    </div>
                </div>
                <!--dashbords-conts-tiles-loader-main-wrap-close-->
                <div class="tile-stats">
                    <div class="icon">
                        <i class="fa fa-car"></i>
                    </div>
                    <div class="count" id="picked_remain">0</div>{{--{{ $mainfest_orders }}</div>--}}
                    <h3>Remaining Packages OFD</h3>
                </div>
            </div>
            <!--Animated-b Div Close-->
    @endif

    @if(in_array($type, ['all']))
        <!--Animated-c Div Open-->
            <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
                <!--dashbords-conts-tiles-loader-main-wrap-open-->
                <div class="dashbords-conts-tiles-loader-main-wrap  total-summary show">
                    <div class="dashbords-conts-tiles-loader-inner-wrap">
                        <div class="lds-roller">
                            <div class="dot-1"></div>
                            <div class="dot-2"></div>
                            <div class="dot-3"></div>
                            <div class="dot-4"></div>
                            <div class="dot-5"></div>
                            <div class="dot-6"></div>
                            <div class="dot-7"></div>
                            <div class="dot-8"></div>
                        </div>
                    </div>
                </div>
                <!--dashbords-conts-tiles-loader-main-wrap-close-->
                <div class="tile-stats">
                    <div class="icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <div class="count" id="route_picked_remain">0
                    </div>{{--{{  isset($amazon_count)?$amazon_count['return_orders']:0 }}</div>--}}
                    <h3>Remaining Routes OFD</h3>
                </div>
            </div>
        @endif

        @if(in_array($type, ['all']))
            <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 dashbords-conts-tiles-main-wrap">
                <!--dashbords-conts-tiles-loader-main-wrap-open-->
                <div class="dashbords-conts-tiles-loader-main-wrap  total-order show">
                    <div class="dashbords-conts-tiles-loader-inner-wrap">
                        <div class="lds-roller">
                            <div class="dot-1"></div>
                            <div class="dot-2"></div>
                            <div class="dot-3"></div>
                            <div class="dot-4"></div>
                            <div class="dot-5"></div>
                            <div class="dot-6"></div>
                            <div class="dot-7"></div>
                            <div class="dot-8"></div>
                        </div>
                    </div>
                </div>
                <!--dashbords-conts-tiles-loader-main-wrap-close-->
                <div class="tile-stats">
                    <div class="icon">
                        <i class="fa fa-percent"></i>
                    </div>
                    <div class="count" id="completion_order">0.00</div>{{--{{ $yesterday_return_orders }}</div>--}}
                    <h3>Order Completion Ratio</h3>
                </div>
            </div>
            <!--Animated-c Div Close-->
        @endif


    </div>
</div>