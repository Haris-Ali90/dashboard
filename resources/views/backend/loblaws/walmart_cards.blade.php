<div class="row" id="walmartCards">
    <!--top_tiles Div Open-->
    <div class="top_tiles montreal-dashbord-tiles" id="walmart-dashbord-tiles-id">

        <!--Animated-a Div Open-->
        <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="count">{{ isset($walmart_count)?$walmart_count->total:0 }}</div>
                <h3>Total Orders</h3>
            </div>
        </div>
        <!--Animated-a Div Close-->

        <!--Animated-b Div Open-->
        <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-truck"></i>
                </div>
                <div class="count">{{ isset($walmart_count)?$walmart_count->atstore:0 }}</div>
                <h3> At Store</h3>
            </div>
        </div>
        <!--Animated-b Div Close-->

        <!--Animated-c Div Open-->
        <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-rotate-left"></i>
                </div>
                <div class="count">{{ isset($walmart_count)?$walmart_count->outfordelivery:0 }}</div>
                <h3> Out For Delivery</h3>
            </div>
        </div>
        <!--Animated-c Div Close-->

        <!--Animated-d Div Open-->
        <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="count">{{ isset($walmart_count)?$walmart_count->deliveredorder:0 }}</div>
                <h3> Delivered Orders</h3>
            </div>
        </div>
        <!--Animated-d Div Close-->

        <!--Animated-e Div Open-->
        <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-car"></i>
                </div>
                <div class="count">{{ isset($walmart_count)?$walmart_count->athub:0 }}</div>
                <h3> At Hub Orders</h3>
            </div>
        </div>
        <!--Animated-e Div Close-->

        <!--Animated-f Div Open-->
        <div class="animated flipInY col-lg-4 col-md-4 col-sm-6 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-car"></i>
                </div>
                <div class="count">{{ isset($walmart_count)?$walmart_count->otd:0 }}</div>
                <h3> OTD</h3>
            </div>
        </div>
        <!--Animated-f Div Close-->


    </div>
    <!--top_tiles Div Close-->

</div>