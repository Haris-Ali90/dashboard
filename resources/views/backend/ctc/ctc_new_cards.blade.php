<div class="row" id="ctcCards">
    <!--top_tiles Div Open-->
    <div class="top_tiles montreal-dashbord-tiles" id="ctc-dashbord-tiles-id">

        <!--Animated-a Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <div class="count">{{ $ctc_count['total'] }}</div>
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
                <div class="count">{{$ctc_count['atstore'] }}</div>
                <h3> At Store</h3>
            </div>
        </div>
        <!--Animated-b Div Close-->

        <!--Animated-c Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-rotate-left"></i>
                </div>
                <div class="count">{{ $ctc_count['outfordelivery'] }}</div>
                <h3> Out For Delivery</h3>
            </div>
        </div>
        <!--Animated-c Div Close-->

        <!--Animated-d Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="count">{{ $ctc_count['deliveredorder'] }}</div>
                <h3> Delivered Orders</h3>
            </div>
        </div>
        <!--Animated-d Div Close-->

        <!--Animated-e Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-car"></i>
                </div>
                <div class="count">{{ $ctc_count['athub'] }}</div>
                <h3> At Hub Orders</h3>
            </div>
        </div>
		
		 <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-car"></i>
                </div>
                <div class="count">{{ $ctc_count['at_unattemp'] }}</div>
                <h3> ReAttempt</h3>
            </div>
        </div>
        <!--Animated-e Div Close-->

        <!--Animated-f Div Open-->
       
        <!--Animated-f Div Close-->


    </div>
    <!--top_tiles Div Close-->

</div>