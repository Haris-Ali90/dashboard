<div class="row" id="montrealCards">
    <!--top_tiles Div Open-->
    <div class="top_tiles montreal-dashbord-tiles" id="montreal-dashbord-tiles-id">

        <!--Animated-a Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="count">{{ $amazon_montreal_count['total_orders'] }}</div>
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
                <div class="count">{{ $amazon_montreal_count['mainfest_orders'] }}</div>
                <h3> Order From Mainfest</h3>
            </div>
        </div>
        <!--Animated-b Div Close-->

        <!--Animated-c Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-rotate-left"></i>
                </div>
                <div class="count">{{ $amazon_montreal_count['return_orders']  }}</div>
                <h3> Returns Orders</h3>
            </div>
        </div>
        <!--Animated-c Div Close-->

        <!--Animated-d Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-sort-amount-asc"></i>
                </div>
                <div class="count">{{ $amazon_montreal_count['sorted_orders']}}</div>
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
                <div class="count">{{ $amazon_montreal_count['pickup_orders'] }}</div>
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
                <div class="count">{{ $amazon_montreal_count['delivered_orders'] }}</div>
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
                <div class="count">{{ $amazon_montreal_count['notscan']  }}</div>
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
                <div class="count">{{ $amazon_montreal_count['failed_orders'] }}</div>
                <h3> Failed Orders</h3>
            </div>
        </div>
        <!--Animated-h Div Close-->


    </div>
    <!--top_tiles Div Close-->

</div>