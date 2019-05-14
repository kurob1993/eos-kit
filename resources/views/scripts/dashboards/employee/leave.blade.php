<script>
    var overtimeChart, overtimeChartHeight, overtimeJson, leaveFilter,
        slfmonth, slfyear, slfboss;
    
    $.ajax({
        url: "{{ route('dashboards.employee.leave.filter') }}"
    })
    .done(function (data) {
        leaveFilter = data;

        $.each(data.lfmonths, function (i, item) {
            $('#filter-leave-month').append($('<option>', { 
                value: item.number, text : item.name 
            }));
        });
        $.each(data.lfyears, function (i, item) {
            $('#filter-leave-year').append($('<option>', { 
                value: item.year, text : item.year 
            }));
        });
        $.each(data.lfsubordinatesboss, function (i, item) {
            $('#filter-leave-boss').append($('<option>', { 
                value : item.personnel_no,
                text: item.name + ' - ' + item.position_name
            }));
        });

        leaveChartClickHandler();
    });

    function leaveChartClickHandler() {
        slfmonth = $('#filter-leave-month option:selected').val();
        slfyear = $('#filter-leave-year option:selected').val();
        slfboss = $('#filter-leave-boss option:selected').val();
        renderLeaveChart();
    }

    function renderLeaveChart() {
        $.ajax({
            url: "{{ route('dashboards.employee.leave') }}",
            data: { lfmonth: slfmonth, lfyear: slfyear, lfboss:slfboss }
        })
        .done(function (data) {
            overtimeJson = data;
            overtimeChartHeight = overtimeJson.dataset[0].data.length * 22;
            overtimeChart = new FusionCharts({
                type: "overlappedbar2d",
                renderAt: "leave-chart",
                width: '100%',
                height: overtimeChartHeight,
                dataFormat: 'json',
                dataSource: overtimeJson,
            }).render();
        });
    }

</script>
