<script>
    var permitChartRendered = false;
    var permitChart, permitChartHeight, permitJson, permitFilter,
        spfmonth, spfyear, spfboss;    

    function permitChartAjax() {
        $.ajax({
            url: "{{ route('dashboards.employee.permit.filter') }}"
        })
        .done(function (data) {
            permitFilter = data;

            $('#filter-permit-month').empty();
            $.each(data.pfmonths, function (i, item) {
                $('#filter-permit-month').append($('<option>', { 
                    value: item.number, text : item.name 
                }));
            });
            $('#filter-permit-year').empty();
            $.each(data.pfyears, function (i, item) {
                $('#filter-permit-year').append($('<option>', { 
                    value: item.year, text : item.year 
                }));
            });
            $('#filter-permit-boss').empty();
            $.each(data.pfsubordinatesboss, function (i, item) {
                $('#filter-permit-boss').append($('<option>', { 
                    value : item.personnel_no,
                    text: item.name + ' - ' + item.position_name
                }));
            });
            permitChartClickHandler();
        });        
    }

    function permitChartClickHandler() {
        spfmonth = $('#filter-permit-month option:selected').val();
        spfyear = $('#filter-permit-year option:selected').val();
        spfboss = $('#filter-permit-boss option:selected').val();
        permitChartRender();
    }

    function permitChartRender() {
        $.ajax({
            url: "{{ route('dashboards.employee.permit') }}",
            data: { pfmonth: spfmonth, pfyear: spfyear, pfboss:spfboss }
        })
        .done(function (data) {
            permitJson = data;
            permitChartHeight = permitJson.data.length * 22;
            permitChart = new FusionCharts({
                type: "bar2d",
                renderAt: "permit-chart",
                width: '100%',
                height: permitChartHeight,
                dataFormat: 'json',
                dataSource: permitJson,
                "events": {
                    "rendered": function (eventObj, dataObj) {
                        permitChartRendered = true;
                    }
                }                
            }).render();
        });
    }
    </script>