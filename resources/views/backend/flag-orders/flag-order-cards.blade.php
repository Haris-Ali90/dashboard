<div class="row" id="flagJoeys">
    {{--<!--top_tiles Div Open-->
    <div class="top_tiles montreal-dashbord-tiles" id="montreal-dashbord-tiles-id">

        <!--Animated-a Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-user"></i>
                </div>
                <div class="count">{{ isset($all_flag_joey) ? count($all_flag_joey) : 0 }}</div>
                <h3 class="flag-head">Total Flag Joeys</h3>
            </div>
        </div>
        <!--Animated-a Div Close-->

        <!--Animated-b Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-tag"></i>
                </div>
                <div class="count">{{ isset($all_flag_mark) ? $all_flag_mark : 0 }}</div>
                <h3 class="flag-head"> Total Flag Mark</h3>
            </div>
        </div>
        <!--Animated-b Div Close-->

       <!--Animated-c Div Open-->
        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-check"></i>
                </div>
                <div class="count">{{ isset($all_approved_flag) ? $all_approved_flag : 0 }}</div>
                <h3 class="flag-head"> Approved Flag Orders</h3>
            </div>
        </div>

        <div class="animated flipInY col-lg-3 col-md-6 col-sm-12 ">
            <div class="tile-stats">
                <div class="icon">
                    <i class="fa fa-ban"></i>
                </div>
                <div class="count">{{ isset($all_un_approved_flag) ? $all_un_approved_flag : 0 }}</div>
                <h3 class="flag-head"> Un-Approved Flag Orders</h3>
            </div>
        </div>
        <!--Animated-c Div Close-->

    </div>
    <!--top_tiles Div Close-->--}}

    <!-- signup stages list - [start] -->
        <div class="stages_list">
            <div class="col col-lg-4 col-md-6 col-sm-12">
                <div class="stage_box link-wrap basicRegistration ">
                    <i class="fa fa-caret-down"></i>
                    <a href="#" class="link" data-id="basicRegistrationTable"></a>
                    <div class="number">{{isset($all_flag_joey) ? count($all_flag_joey) : 0}}</div>
                    <div class="label">Total Flag Joeys</div>
                </div>
            </div>
            <div class="col col-lg-4 col-md-6 col-sm-12">
                <div class="stage_box link-wrap">
                    <i class="fa fa-caret-down"></i>
                    <a href="#" class="link" data-id="totalApplicationSubmissionTable"></a>
                    <div class="number">{{ isset($all_flag_mark) ? $all_flag_mark : 0 }}</div>
                    <div class="label">Total Flag Mark</div>
                </div>
            </div>
            <div class="col col-lg-4 col-md-6 col-sm-12">
                <div class="stage_box link-wrap">
                    <i class="fa fa-caret-down"></i>
                    <a href="#" class="link" data-id="totalTrainingwatchedTable"></a>
                    <div class="number">{{ isset($all_approved_flag) ? $all_approved_flag : 0 }}</div>
                    <div class="label">Approved Flag Orders</div>
                </div>
            </div>
        </div>
</div>
<div class="row" id="flagJoeys">
    <div class="stages_list">
            <div class="col col-lg-4 col-md-6 col-sm-12">
                <div class="stage_box link-wrap">
                    <i class="fa fa-caret-down"></i>
                    <a href="#" class="link" data-id="totalQuizPassedTable"></a>
                    <div class="number">{{ isset($all_un_approved_flag) ? $all_un_approved_flag : 0 }}</div>
                    <div class="label">Un-Approved Flag Orders</div>
                </div>
            </div>
            <div class="col col-lg-4 col-md-6 col-sm-12">
                <div class="stage_box link-wrap">
                    <i class="fa fa-caret-down"></i>
                    <a href="#" class="link" data-id="totalQuizPassedTable"></a>
                    <div class="number">{{ isset($all_un_flag_mark) ? $all_un_flag_mark : 0 }}</div>
                    <div class="label">Un-Flag Orders</div>
                </div>
            </div>
            <div class="col col-lg-4 col-md-6 col-sm-12">
                <div class="stage_box link-wrap">
                    <i class="fa fa-caret-down"></i>
                    <a href="#" class="link" data-id="totalQuizPassedTable"></a>
                    <div class="number">{{ isset($blocked_joeys_by_flag) ? count($blocked_joeys_by_flag) : 0 }}</div>
                    <div class="label">Joeys Blocked By Flag</div>
                </div>
            </div>
        </div>
        <!-- signup stages list - [/end] -->
</div>
<style>
    .stages_list{}
    .stages_list:after{content:""; display: block; clear: both;}
    .stages_list .col{padding: 8px 12px; /*width: 20%;*/ float: left;}
    .stages_list .stage_box{ padding: 20px 5px 12px; box-shadow: 0 4px 16px #e6e6e6; border: solid 3px #fff; background: #fff; text-align: center; transition: all 0.2s ease-in-out;
        border-radius: 10px !important;
    }
    .stages_list .stage_box .number{ color: #e36d29; font-size: 32px; font-weight: bold; margin-bottom: 0px; line-height: 1em;}
    .stages_list .stage_box .label{ font-size: 15px; line-height: 1.4em; color: #666666; white-space: normal; min-height: 46px; display: flex; align-items: center; justify-content: center; }

    .stages_list .col .stage_box .fa{display: none; }
    .stages_list .col.active .stage_box{ border-color: #e36d29 !important;}
    .stages_list .col.active .stage_box .fa{display: block; color: #e36d29; position: absolute; bottom: -26px; left: 50%; margin-left: -10px; width: 25px; height: 25px; font-size: 40px;}

    @media only screen and (max-width: 767px){
        .stages_list .col{width: 50%;}
        .stages_list .stage_box{}
    }
    @media only screen and (max-width: 480px){
        .stages_list .col{width: 100%;}
    }
</style>