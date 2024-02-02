
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
        //Table Chart
        google.charts.load('current', {'packages':['table']});
        google.charts.setOnLoadCallback(drawTable);

        function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('number', 'Orders');
            data.addRows([
                ['Montreal',  {f: '{{ isset($montreal_count)?$montreal_count->total:0 }}'}, ],
                ['Ottawa',   {f: '{{ isset($ottawa_count)?$ottawa_count->total:0 }}'},  ],
                ['Canadian Tire', {f: '{{ isset($ctc_count)?$ctc_count->total:0 }}'}, ]
            ]);

            var table = new google.visualization.Table(document.getElementById('table_div'));

            table.draw(data, {showRowNumber: true, width: '100%', height: '100%'});
        }

    </script>


<div class="col-sm-6 dashboard-statistics-box dashboard-statistics-tbl-show" id="table_div"></div>

