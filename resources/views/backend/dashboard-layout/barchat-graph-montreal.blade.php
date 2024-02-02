
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    //Bar Chart
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    // making labels
    var dataArray = [
        ['Month', 'Amazon Montreal Orders'],
    ];
    // geting month data
    var bar_chart_data =  JSON.parse( '{!!$bar_chart_data_list_array !!}');
    // converting json into array
    for(index in bar_chart_data)
    {
        dataArray.push([bar_chart_data[index].month,bar_chart_data[index].montreal_total]);
    }

    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataArray);
        var options = {
            /*chart: {
                title: 'Joeyco Activities',
            }*/
            title: ' ',
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_monteral'));
        chart.draw(data, google.charts.Bar.convertOptions(options));
    }

</script>

<div class="col-sm-6 dashboard-statistics-box">
    <div class="x_panel">
        <div class="x_title">
            <h2>Montreal Activities</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <div class="dashboard-statistics-box" id="columnchart_monteral"></div>

        </div>
    </div>
</div>

{{--<div class="col-sm-6 dashboard-statistics-box" id="columnchart_monteral"></div>--}}
