<script>
    var  overtimeChart, overtimeChartHeight, overtimeJson;
    $.ajax({
        url: "{{ route('dashboards.employee.leave') }}"
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
</script>
