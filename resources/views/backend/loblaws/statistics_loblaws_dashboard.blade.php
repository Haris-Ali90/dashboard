<?php

$user = Auth::user();
if ($user->email != "admin@gmail.com") {

    $data = explode(',', $user['rights']);
    $dataPermission = explode(',', $user['permissions']);
} else {
    $data = [];
    $dataPermission = [];
}

?>
@extends( 'backend.layouts.app' )

@section('title', 'Loblaws Dashboard')

@section('CSSLibraries')
    <!-- DataTables CSS -->
    <link href="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.css') }}" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.css') }}" rel="stylesheet">
    <!-- Image Viewer CSS -->
    <link href="{{ backend_asset('libraries/galleria/colorbox.css') }}" rel="stylesheet">
    <style>
      .bg-green {
    background: #c6dd38 !important;
    border: 1px solid #34495e  !important;
    color: #fff;
}
    .loblaws-statistics-checkbox-wrap {
        min-height: 71px;
        border: 1px solid #eee;
        position: relative;
    padding-right: 30px;
    background: #c6dd38;
    }
    .loblaws-statistics-checkbox-wrap .icon {
    position: absolute;
    top: 40%;
    right: 3%;
    }
    .loblaws-statistics-dashboard-btn-warp .loblaws-statistics-checkbox-wrap label
    {
        font-size: 12px;
        margin: 0px;
        color: #000;
    }

    .loblaws-statistics-dashboard-btn-warp .loblaws-statistics-checkbox
    {
        width: 15px !important;
        margin-top: 0px;
    }
    .loblaws-statistics-show-box-inner-wrap {
        background-color: #fff;
        margin: 15px 0px;
    }
    .loblaws-statistics-show-box-heading-wrap {
        text-align: center;
        margin: 0px;
        padding: 10px 0px 20px;
        color: #3e3e3e;
    }
    ////
    .loblaws-statistics-show-box-main-wrap
    {
        display: none;
        position: relative;
    }
    .loblaws-statistics-checkbox-wrap {
        border: 1px solid #eee;
    }
    .loblaws-statistics-dashboard-btn-warp .wm-statistics-checkbox-wrap label
    {
        font-size: 12px;
        margin: 0px;
        color: #000;
    }

    .loblaws-statistics-dashboard-btn-warp .wm-statistics-checkbox
    {
        width: 15px !important;
        margin-top: 0px;
    }
    .loblaws-statistics-show-box-inner-wrap {
        background-color: #fff;
        margin: 15px 0px;
    }
    .loblaws-statistics-show-box-heading-wrap {
        text-align: center;
        margin: 0px;
        padding: 10px 0px 20px;
        color: #3e3e3e;
    }


    /*loader box css*/
    .statistics-ajax-data-loader-wrap {
        position: absolute;
        top: 0px;
        left: 0px;
        z-index: 9999;
        background-color: #0000005e;
        width: 100%;
        height: 95%;
        text-align: center;
        display: none;
    }
    .statistics-ajax-data-loader-wrap.show {
        display: block !important;
    }
    .statistics-ajax-data-loader-inner-wrap {
        position: relative;
        top: 0%;
    }
    .statistics-ajax-data-loader-inner-wrap p {
        color: #fff;
        font-size: 17px;
        position: relative;
        bottom: 15px;
    }
    .lds-facebook {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }
    .lds-facebook div {
        display: inline-block;
        position: absolute;
        left: 8px;
        width: 16px;
        background: #fff;
        animation: lds-facebook 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
    }
    .lds-facebook div:nth-child(1) {
        left: 8px;
        animation-delay: -0.24s;
    }
    .lds-facebook div:nth-child(2) {
        left: 32px;
        animation-delay: -0.12s;
    }
    .lds-facebook div:nth-child(3) {
        left: 56px;
        animation-delay: 0;
    }
    @keyframes lds-facebook {
        0% {
            top: 8px;
            height: 64px;
        }
        50%, 100% {
            top: 24px;
            height: 32px;
        }
    }
	
	
    </style>
@endsection


@section('content')

    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left amazon-text">
                    <h3 class="text-center">Loblaws Dashboard<small></small></h3>
                </div>
            </div>

            <div class="clearfix"></div>
            <!--Count Div Row Open-->
        {{--@include('backend.ctc.ctc_cards')--}}
        <!--Count Div Row Close-->
            {{--@include('backend.layouts.modal')
            @include( 'backend.layouts.popups')--}}

            <!--row-open-->
            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                    <div class="x_title col-md-12">
                        <form method="get" action="">
                              
								 <div class="row">
                                <div class="col-md-3">
                                 <label>Search By Date</label>
                                 <input type="date" name="date" class="form-data form-control" value='<?php echo  $date ?>' required="" placeholder="Search">
                                
                                </div>
                                <div class="col-md-3">
                               <button class="btn btn-primary mb1 bg-green"  type="submit" style="    margin-top: 24px;">Go</a> </button>
                                </div>


                            </div>
                           </form>
                             <h2>Loblaws <small>Loblaws  Dashboard</small></h2>
                        <div  class=" text-right"><button  target="_blank" onclick="exportTableToCSV('loblaws-reprt-<?php echo $date ?>.csv')"  class="btn btn-success pl-md-3">Export CSV</button></div>
                            <div class="clearfix"></div>
                        </div>
                        

                        <!-- loblaws-statistics-dashboard-btn-warp-open-->
                        <div class="col-sm-12 loblaws-statistics-dashboard-btn-warp">
                            <!--<div class="col-sm-12">
                                <label>Date :</label>
                                <input class="form-control" type="date"/>
                            </div>-->

                            <div class="col-sm-2 loblaws-statistics-checkbox-wrap">
                                <label>OTD Graph:</label>
                                <input class="form-control loblaws-statistics-checkbox" type="checkbox"  data-status="false" data-for="loblaws-otd" data-targeted-div="otd-main-wrap" />
                                <div class="icon">
                        <i class="fa fa-pie-chart" style=" font-size: x-large;color: #e36d28;"></i>
                   </div>
                            </div>
                           

                            <div class="col-sm-2 loblaws-statistics-checkbox-wrap">
                                <label>All Orders Data :</label>
                                <input class="form-control loblaws-statistics-checkbox" type="checkbox"  data-status="false" data-for="loblaws-orders" data-targeted-div="loblaws-orders-main-wrap"/>
                                <div class="icon">
                        <i class="fa fa-cubes" style=" font-size: x-large;color: #e36d28;"></i>
                   </div>
                            </div>

                            <div class="col-sm-2 loblaws-statistics-checkbox-wrap">
                                <label>On Time Graph:</label>
                                <input class="form-control loblaws-statistics-checkbox" type="checkbox"  data-status="false" data-for="loblaws-ota" data-targeted-div="lablaws-ota-main-wrap"/>
                                <div class="icon">
                        <i class="fa fa-bar-chart" style=" font-size: x-large;color: #e36d28;"></i>
                   </div>
                            </div>
                            
                        </div>
						                        <!-- loblaws-statistics-dashboard-btn-warp-close-->


                    </div>

                </div>

                <!--loblaws-statistics-show-box-main-wrap-open-->
                <!-- <div class="col-md-6 loblaws-statistics-show-box-main-wrap otd-main-wrap otd-joeyco-exprince-main-wrap"> -->

                    <!--loblaws-statistics-show-box-inner-wrap-open-->
                    <!-- <div class="col-md-12 loblaws-statistics-show-box-inner-wrap"> -->
                        <!--loblaws-statistics-show-box-heading-wrap-open-->
                        <!-- <div class="col-md-12 loblaws-statistics-show-box-heading-wrap">
                            <h2>OTD JoeyCo exprince</h2>
                        </div> -->
                        <!--loblaws-statistics-show-box-heading-wrap-close-->

                        <!--loblaws-statistics-show-box-heading-wrap-open-->
                        <!-- <div class="col-md-12 loblaws-statistics-show-box-data-wrap otd otd-joeyco-exprince">
                            <div id="cExp"></div>
                        </div> -->
                        <!--loblaws-statistics-show-box-heading-wrap-close-->

                    <!-- </div> -->
                    <!--loblaws-statistics-show-box-inner-wrap-close-->

                <!-- </div> -->
                <!--loblaws-statistics-show-box-main-wrap-close-->


                <!--loblaws-statistics-show-box-main-wrap-open-->
                 <div class="col-md-12 loblaws-statistics-show-box-main-wrap otd-main-wrap otd-overall-exprince-main-wrap">
                  <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                  <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--loblaws-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 loblaws-statistics-show-box-inner-wrap">
                        <!--loblaws-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 loblaws-statistics-show-box-heading-wrap">
                            <h2>OTD Graph</h2>
                        </div>
                        <!--loblaws-statistics-show-box-heading-wrap-close-->

                        <!--loblaws-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 loblaws-statistics-show-box-data-wrap loblaws-otd">
                            <div id="pie_chart"></div>
                        </div>
                        <!--loblaws-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--loblaws-statistics-show-box-inner-wrap-close-->
                
                </div>
                <!--loblaws-statistics-show-box-main-wrap-close-->

               <!--loblaws-statistics-show-box-main-wrap-open-->
               <div class="col-md-12 loblaws-statistics-show-box-main-wrap loblaws-orders-main-wrap">
                  <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                  <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--loblaws-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 loblaws-statistics-show-box-inner-wrap">
                        <!--loblaws-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 loblaws-statistics-show-box-heading-wrap">
                            <h2>Loblaws Orders </h2>
                        </div>
                        <!--loblaws-statistics-show-box-heading-wrap-close-->

                        <!--loblaws-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 loblaws-statistics-show-box-data-wrap loblaws-orders">

                        </div>
                        <!--loblaws-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--loblaws-statistics-show-box-inner-wrap-close-->

                    </div>
                    <!--loblaws-statistics-show-box-main-wrap-close-->

                <!--loblaws-statistics-show-box-main-wrap-open-->
                <div class="col-md-12 loblaws-statistics-show-box-main-wrap lablaws-ota-main-wrap">
                  <!--ajax-statistics-ajax-data-loader-wrap-loader-open-->
                  <div class="statistics-ajax-data-loader-wrap ">
                        <div class="statistics-ajax-data-loader-inner-wrap">
                            <div class="lds-facebook"><div></div><div></div><div></div></div>
                            <p>Loading...</p>
                        </div>
                    </div>
                    <!--ajax-statistics-ajax-data-loader-wrap-loader-close-->

                    <!--loblaws-statistics-show-box-inner-wrap-open-->
                    <div class="col-md-12 loblaws-statistics-show-box-inner-wrap">
                        <!--loblaws-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 loblaws-statistics-show-box-heading-wrap">
                            <h2>On Time Graph</h2>
                        </div>
                        <!--loblaws-statistics-show-box-heading-wrap-close-->

                        <!--loblaws-statistics-show-box-heading-wrap-open-->
                        <div class="col-md-12 loblaws-statistics-show-box-data-wrap loblaws-ota">
                            <div id="on_time_orders"></div>
                        </div>
                        <!--loblaws-statistics-show-box-heading-wrap-close-->

                    </div>
                    <!--loblaws-statistics-show-box-inner-wrap-close-->

                </div>
                <!--loblaws-statistics-show-box-main-wrap-close-->

            </div>
            <!--row-open-->


        </div>
    </div>
    <!-- /#page-wrapper -->

    <!-- <script src="{{ backend_asset('js/jquery-1.12.4.js') }}"></script>
<script src="{{ backend_asset('js/jquery-ui.js') }}"></script> -->

    <!-- <script src="{{ backend_asset('js/gm-date-selector.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.css') }}"></script>
<script src="{{ backend_asset('css/bootstrap.js') }}"></script>
 -->

    <!-- <script src="{{ backend_asset('js/bootstrap.js') }}"></script> -->



@endsection

@section('JSLibraries')
    <!-- DataTables JavaScript -->
    <script src="{{ backend_asset('libraries/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-plugins/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ backend_asset('libraries/datatables-responsive/dataTables.responsive.js') }}"></script>
    <script src="{{ backend_asset('libraries/galleria/jquery.colorbox.js') }}"></script>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <script src="{{ backend_asset('js/jquery-ui.js') }}"></script>
    <link href="{{ backend_asset('js/jquery-ui.css') }}" rel="stylesheet"> -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
@endsection


@section('inlineJS')
    <script>


function exportTableToCSV(filename) {
    var csv = [];
    var rows = document.querySelectorAll("#export-csv tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length-1; j++) 
            row.push(cols[j].innerText.replace(',',' ').replace(',',' ').replace(',',' ').replace(',',' '));
        
        csv.push(row.join(","));        
    }

    // Download CSV file
    downloadCSV(csv.join("\n"), filename);
 }

 function downloadCSV(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV file
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();



}
      // check box functions 
      $('.loblaws-statistics-checkbox').click(function () {

          let el = $(this);
          var url_for =  el.attr('data-for');
          var status =  el.attr('data-status');
          let targeted_div =  el.attr('data-targeted-div');
        
          console.log($('.'+url_for).text().length)
          var s=status.localeCompare('false');
        
            if(!status.localeCompare('false'))
            {

                $('.'+url_for).show();
                if($('.'+url_for).text().length<55)
                {
                    $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').addClass('show');
         
                    walmart_ajax_statistics_call(el,url_for,targeted_div); 
                }
                
                $(this).attr('data-status','true');
            }
            else
            {
                $('.'+url_for).hide();
                $(this).attr('data-status','false');
            }
         
         

      });


      function walmart_ajax_statistics_call(el,url_for,targeted_div) {

        var date=document.getElementsByName('date')[0].value;

          //window.location.href =  '{{ URL::to('dashboard/statistics/ajax/')}}/'+url_for;
          $.ajax({
              type: "get",
              url: '{{ URL::to('dashboard/statistics/ajax/')}}/'+url_for,
              data:{'date':date},
              success: function (data) {
                $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');
                  if(data.status = true)
                  {

                      if(data['for'] == 'pie_chart')
                      {
                          show_odt_pichart(data.data);
                      }
                      else if(data['for'] == 'loblaws-ota')
                      {
                          show_loblaws_otd_bar_chart_two(data)
                      }
                      else
                      {
                          $('.'+data['for']).html(data.html);
                      }

                  }
                  else
                  {

                  }

              },
              error:function (error) {
                $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');
                  alert('some error');
              }
          });

      }


    
    function show_odt_pichart(data) {

          Highcharts.chart('pie_chart', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    colors: [
                        '#b6d309',
                        '#f1732a'
                    ],
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Deliveries',
                colorByPoint: true,
                data: [{
              
                    name: data[0]['tag1'],
                    y:data[0]['y1'],
                    //round((data.otd/data.total)*100,0),
                    sliced: true,
                    selected: true
                }, {
                    name: data[0]['tag2'],
                    y: data[0]['y2']
                    //100-round((data.otd/data.total)*100,0)
                }, ]
            }],exporting: {
                  enabled: false
              }
        });


    }


    function show_loblaws_otd_bar_chart_one(data) {

        Highcharts.chart('loblaws-orders',{
            chart: {
                type: 'column'
            },
            title: {
                text: 'Walmart Orders'
            },
            xAxis: {
                categories:1
            },
            yAxis: [{
                min: 0,
                title: {
                    text: 'Deliveries'
                }
            }, {
                title: {
                    text: ''
                },
                opposite: true
            }],
            legend: {
                shadow: false
            },
            tooltip: {
                shared: true
            },
            series: [{
                name: 'Deliveries',
                color: 'rgb(183, 212, 9)',
                data: data.data_set_one,
                yAxis: 1
            }, {
                name: 'Lates',
                color: 'rgb(221, 105, 39)',
                data: data.data_set_two,
                yAxis: 1
            }],exporting: {
                enabled: false
            }
        });
    }

      function show_loblaws_otd_bar_chart_two(data) {
        console.log(data.data_set_one);
          Highcharts.chart('on_time_orders',{
            chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            categories: 1
        },
        yAxis: [{
            min: 0,
            title: {
                text: 'On Time Arrival'
            }
        }, {
            title: {
                text: 'On Time Delivery'
            },
            opposite: true
        }],
        legend: {
            shadow: false
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
           },
        series: [{
            name: 'OTA',
            color: 'rgb(59, 59, 59)',
            data: data.data_set_one,
            yAxis: 1
        }, {
            name: 'OTD',
            color: 'rgb(183, 212, 9)',
            data: data.data_set_two,
            yAxis: 1
        }],exporting: {
                  enabled: false
              }
          });
      }

      $(document).on('click','.paginationbt',function(){
        let page_no =  $(this).attr('data-id');
        let targeted_div =  $(this).attr('data-targeted-div');
          $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').addClass('show');
          
        let date=document.getElementsByName('date')[0].value;

        $.ajax({
              type: "get",
              url: "{{ URL::to('dashboard/statistics/ajax/loblaws-orders')}}",
              data:{page:page_no,date:date},
              success: function (data) {
                $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');
                  if(data.status = true)
                  {
                    $('.'+data.for).html('');
                  $('.'+data.for).html(data.html);
                  }
                  else
                  {

                  }

              },
              error:function (error) {
                $('.'+targeted_div).find('.statistics-ajax-data-loader-wrap').removeClass('show');
                  alert('some error');
              }
          });
      });

     
    </script>

@endsection