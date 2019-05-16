<script>
    var timeEventChartRendered = false;
    var timeEventChart, timeEventChartHeight, timeEventJson, timeEventFilter,
        stemonth, steyear, steboss;    

    function timeEventChartAjax() {
        $.ajax({
            url: "{{ route('dashboards.employee.time_event.filter') }}"
        })
        .done(function (data) {
            timeEventFilter = data;
            $('#filter-time-event-month').empty();
            $.each(data.temonths, function (i, item) {
                $('#filter-time-event-month').append($('<option>', { 
                    value: item.number, text : item.name 
                }));
            });
            $('#filter-time-event-year').empty();
            $.each(data.teyears, function (i, item) {
                $('#filter-time-event-year').append($('<option>', { 
                    value: item.year, text : item.year 
                }));
            });
            $('#filter-time-event-boss').empty();
            $.each(data.tesubordinatesboss, function (i, item) {
                $('#filter-time-event-boss').append($('<option>', { 
                    value : item.personnel_no,
                    text: item.name + ' - ' + item.position_name
                }));
            });
            timeEventChartClickHandler();
        });        
    }

    function timeEventChartClickHandler() {
        stemonth = $('#filter-time-event-month option:selected').val();
        steyear = $('#filter-time-event-year option:selected').val();
        steboss = $('#filter-time-event-boss option:selected').val();
        timeEventChartRender();
    }

    function timeEventChartRender() {
        $.ajax({
            url: "{{ route('dashboards.employee.time_event') }}",
            data: { temonth: stemonth, teyear: steyear, teboss:steboss }
        })
        .done(function (data) {
            timeEventJson = data;
            timeEventChartHeight = timeEventJson.data.length * 42;
            timeEventChart = new FusionCharts({
                type: "bar2d",
                renderAt: "time-event-chart",
                width: '100%',
                height: timeEventChartHeight,
                dataFormat: 'json',
                dataSource: timeEventJson,
                "events": {
                    "rendered": function (eventObj, dataObj) {
                        timeEventChartRendered = true;
                    },
                    "noDataToDisplay": function (eventObj) {
                        $('#time-event-chart').text('Tidak ada data yang ditemukan.');
                    }
                }                
            }).render();
        });
    }
    </script>