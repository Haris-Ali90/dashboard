<div class="row" id="montrealCards">
    <!--top_tiles Div Open-->
    <div class="top_tiles montreal-dashbord-tiles" id="montreal-dashbord-tiles-id">

        <!--Animated-a Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="count">{{ isset($amazon_montreal_count)?$amazon_montreal_count->total:0 }}</div>
                <h3>Total Orders</h3>
            </div>
        </div>
        <!--Animated-a Div Close-->

        <!--Animated-b Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-truck"></i>
                </div>
                <div class="count">{{ isset($amazon_montreal_count)?$amazon_montreal_count->mainfestOrders:0 }}</div>
                <h3> Order From Manifest</h3>
            </div>
        </div>
        <!--Animated-b Div Close-->

       <!--Animated-c Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-rotate-left"></i>
                </div>
                <div class="count">{{ isset($amazon_montreal_count)?$amazon_montreal_count->returnOrder:0 }}</div>
                <h3> Today Returns Orders</h3>
            </div>
        </div>

        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-rotate-left"></i>
                </div>
                <div class="count">{{ isset($amazon_montreal_count)?$amazon_montreal_count->yesterdayReturnOrder:0 }}</div>
                <h3> Yesterday Returns Orders</h3>
            </div>
        </div>
        <!--Animated-c Div Close-->

        <!--Animated-d Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-sort-amount-asc"></i>
                </div>
                <div class="count">{{ isset($amazon_montreal_count)?$amazon_montreal_count->sorter_order_count:0 }}</div>
                <h3> Total Sorted Orders</h3>
            </div>
        </div>
        <!--Animated-d Div Close-->

        <!--Animated-e Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-car"></i>
                </div>
                <div class="count">{{ isset($amazon_montreal_count)?$amazon_montreal_count->hub_deliver_order_count:0 }}</div>
                <h3> Picked up from Hub Orders</h3>
            </div>
        </div>
        <!--Animated-e Div Close-->

        <!--Animated-f Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="count">{{ isset($amazon_montreal_count)?$amazon_montreal_count->deliver_order_count:0 }}</div>
                <h3> Total Delivered Orders</h3>
            </div>
        </div>
        <!--Animated-f Div Close-->

        <!--Animated-g Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div class="count">{{ $notscan_count }}</div>
                <h3> Not Scan</h3>
            </div>
        </div>
        <!--Animated-g Div Close-->

        <!--Animated-h Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="count">{{ isset($amazon_montreal_count)?$amazon_montreal_count->failedOrders:0 }}</div>
                <h3> Failed Orders</h3>
            </div>
        </div>
        <!--Animated-h Div Close-->


    </div>
    <!--top_tiles Div Close-->

</div>