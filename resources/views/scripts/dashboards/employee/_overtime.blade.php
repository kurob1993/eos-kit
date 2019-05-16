<script>
    var overtimeChartRendered = false;
    var overtimeChart, overtimeChartHeight, overtimeJson, overtimeFilter,
        sofmonth, sofyear, sofboss;    

    function overtimeChartAjax() {
        $.ajax({
            url: "{{ route('dashboards.employee.overtime.filter') }}"
        })
        .done(function (data) {
            overtimeFilter = data;
            $('#filter-overtime-month').empty();
            $.each(data.ofmonths, function (i, item) {
                $('#filter-overtime-month').append($('<option>', { 
                    value: item.number, text : item.name 
                }));
            });
            $('#filter-overtime-year').empty();
            $.each(data.ofyears, function (i, item) {
                $('#filter-overtime-year').append($('<option>', { 
                    value: item.year, text : item.year 
                }));
            });
            $('#filter-overtime-boss').empty();
            $.each(data.ofsubordinatesboss, function (i, item) {
                $('#filter-overtime-boss').append($('<option>', { 
                    value : item.personnel_no,
                    text: item.name + ' - ' + item.position_name
                }));
            });
            overtimeChartClickHandler();
        });        
    }

    function overtimeChartClickHandler() {
        sofmonth = $('#filter-overtime-month option:selected').val();
        sofyear = $('#filter-overtime-year option:selected').val();
        sofboss = $('#filter-overtime-boss option:selected').val();
        overtimeChartRender();
    }

    function overtimeChartRender() {
        $.ajax({
            url: "{{ route('dashboards.employee.overtime') }}",
            data: { ofmonth: sofmonth, ofyear: sofyear, ofboss:sofboss }
        })
        .done(function (data) {
            overtimeJson = data;
            l = overtimeJson.data.length;
            overtimeChartHeight = ( l > 13) ? l * 30 : 400;            
            overtimeChart = new FusionCharts({
                type: "bar2d",
                renderAt: "overtime-chart",
                width: '100%',
                height: overtimeChartHeight,
                dataFormat: 'json',
                dataSource: overtimeJson,
                "events": {
                    "rendered": function (eventObj, dataObj) {
                        overtimeChartRendered = true;
                    },
                    "noDataToDisplay": function (eventObj) {
                        $('#overtime-chart').text('Tidak ada data yang ditemukan.');
                    }
                }                
            }).render();
        });
    }
    </script>