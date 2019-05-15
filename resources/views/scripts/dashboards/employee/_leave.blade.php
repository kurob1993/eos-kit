<script>
    var leaveChartRendered = false;
    var leaveChart, leaveChartHeight, leaveJson, leaveFilter,
        slfmonth, slfyear, slfboss;    

    function leaveChartAjax() {
        $.ajax({
            url: "{{ route('dashboards.employee.leave.filter') }}"
        })
        .done(function (data) {
            leaveFilter = data;

            $('#filter-leave-month').empty();
            $.each(data.lfmonths, function (i, item) {
                $('#filter-leave-month').append($('<option>', { 
                    value: item.number, text : item.name 
                }));
            });
            $('#filter-leave-year').empty();
            $.each(data.lfyears, function (i, item) {
                $('#filter-leave-year').append($('<option>', { 
                    value: item.year, text : item.year 
                }));
            });
            $('#filter-leave-boss').empty();
            $.each(data.lfsubordinatesboss, function (i, item) {
                $('#filter-leave-boss').append($('<option>', { 
                    value : item.personnel_no,
                    text: item.name + ' - ' + item.position_name
                }));
            });
            leaveChartClickHandler();
        });        
    }

    function leaveChartClickHandler() {
        slfmonth = $('#filter-leave-month option:selected').val();
        slfyear = $('#filter-leave-year option:selected').val();
        slfboss = $('#filter-leave-boss option:selected').val();
        leaveChartRender();
    }

    function leaveChartRender() {
        $.ajax({
            url: "{{ route('dashboards.employee.leave') }}",
            data: { lfmonth: slfmonth, lfyear: slfyear, lfboss:slfboss }
        })
        .done(function (data) {
            leaveJson = data;
            leaveChartHeight = leaveJson.dataset[0].data.length * 22;
            leaveChart = new FusionCharts({
                type: "overlappedbar2d",
                renderAt: "leave-chart",
                width: '100%',
                height: leaveChartHeight,
                dataFormat: 'json',
                dataSource: leaveJson,
                "events": {
                    "rendered": function (eventObj, dataObj) {
                        leaveChartRendered = true;
                    }
                }                
            }).render();
        });
    }
</script>