{{--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>--}}
{{--<script type="text/javascript">--}}
{{--//3D Pie Chart--}}
{{--google.charts.load("current", {packages:["corechart"]});--}}
{{--google.charts.setOnLoadCallback(drawChart);--}}
{{--function drawChart() {--}}
{{--var data = google.visualization.arrayToDataTable([--}}
{{--['Task', 'Hours per Day'],--}}
{{--['At Store',     {{ isset($ctc_count)?$ctc_count->atstore:0 }}],--}}
{{--['At Hub Orders',     {{ isset($ctc_count)?$ctc_count->athub:0 }}],--}}
{{--['Out For Delivery',      {{ isset($ctc_count)?$ctc_count->outfordelivery:0 }}],--}}
{{--['Delivered Orders',  {{ isset($ctc_count)?$ctc_count->deliveredorder:0 }}]--}}


{{--]);--}}
{{--console.log(data);--}}
{{--var options = {--}}
{{--title: 'Ctc Orders',--}}

{{--};--}}

{{--var chart = new google.visualization.PieChart(document.getElementById('piechart_ctc'));--}}
{{--chart.draw(data, options);--}}
{{--}--}}
{{--</script>--}}

{{--<div class="col-sm-6 dashboard-statistics-box" id="piechart_ctc"></div>--}}


@section('ctc-script')
    <script src="{{ backend_asset('js/echarts.min.js') }}"></script>
    <script type="text/javascript">
        if ($('#ctc_pie').length) {

            var echartPie = echarts.init(document.getElementById('ctc_pie'));

            echartPie.setOption({
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    x: 'center',
                    y: 'bottom',
                    data: ['At Store', 'At Hub Orders', 'Out For Delivery', 'Delivered Orders']
                },
                toolbox: {
                    show: true,
                    feature: {
                        magicType: {
                            show: true,
                            type: ['pie', 'funnel'],
                            option: {
                                funnel: {
                                    x: '25%',
                                    width: '50%',
                                    funnelAlign: 'left',
                                    max: 1548
                                }
                            }
                        }
                    }
                },
                calculable: true,
                series: [{
                    name: 'CTC Dashboard',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '48%'],
                    data: [{
                        value: "{{ isset($ctc_count)?$ctc_count->atstore:0 }}",
                        name: 'At Store'
                    }, {
                        value: "{{ isset($ctc_count)?$ctc_count->athub:0 }}",
                        name: 'At Hub Orders'
                    }, {
                        value: "{{ isset($ctc_count)?$ctc_count->outfordelivery:0 }}",
                        name: 'Out For Delivery'
                    }, {
                        value: "{{ isset($ctc_count)?$ctc_count->deliveredorder:0 }}",
                        name: 'Delivered Orders'
                    }]
                }]
            });

        }

    </script>
@stop
<!--E-chart Pie-->


<!-- ottawa Graph -->
@if($graph == 'ctc')
    @if (count($data)== 0)
        <div class="x_panel">
            <div class="x_title">
                <form method="get" action="ctc">
                    <label>Search By Date</label>
                    <input type="date" name="datepicker" class="data-selector" required=""
                           value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}"
                           placeholder="Search">
                    <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">
                        Go
                    </button>
                </form>

                <div class="clearfix"></div>
            </div>
        </div>

    @elseif (count($data)>0 &&in_array("statistics", $data))
        <div class="row">
            @if(in_array("ctc_dashboard", $data))
                <div class="x_title">
                    <form method="get" action="ctc">
                        <label>Search By Dateablabel>
                            <input type="date" name="datepicker" class="data-selector" required=""
                                   value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}"
                                   placeholder="Search">
                            <button class="btn btn-primary" type="submit" style="margin-top: -3%,4%">
                                Go
                            </button>
                    </form>

                    <div class="clearfix"></div>
                </div>
            @endif
        </div>
    @else
        <input type="hidden" class="data-selector" name="datepicker"
               value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
    @endif
@else
    <input type="hidden" class="data-selector" name="datepicker"
           value="{{ isset($_GET['datepicker'])?$_GET['datepicker']: date('Y-m-d') }}">
@endif
<!-- End ottawa Graph -->


<div class="col-sm-6 dashboard-statistics-box">
    <div class="x_panel">
        <div class="x_title">
            <h2>Canadian Tire</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <div class="dashboard-statistics-box" id="ctc_pie"></div>

        </div>
    </div>
</div>


<!--E-chart Pie-->

