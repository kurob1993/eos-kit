<script>
    jQuery.ajaxSetup({
        beforeSend: function() {
            $.LoadingOverlay("show");
        },
        complete: function(){
            $.LoadingOverlay("hide");
        },
        success: function() {}
        });    
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href"); // alert(target);
        
        switch (target) {
            case '#tab-leaves':
                if (!leaveChartRendered) leaveChartAjax();
                break;
            case '#tab-overtimes':
                if (!overtimeChartRendered) overtimeChartAjax();
                break;
            case '#tab-permits':
                if (!permitChartRendered) permitChartAjax();
                break;
            case '#tab-time-events':
                if (!timeEventChartRendered) timeEventChartAjax();
                break;
            default:
                break;
        }
    });
</script>
