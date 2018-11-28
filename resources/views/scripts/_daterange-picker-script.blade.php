<script type="text/javascript">
    (handleDateRangePicker = function() {
    $("#datepicker-range").datepicker({
      inputs: $("#datepicker-range-start, #datepicker-range-end"),
      format: 'yyyy-mm-dd',
        todayHighlight: true,
        startDate: {{ $start_date }},
        endDate: {{ $end_date }},
        // datesDisabled: ['2018-09-01'],    
    });
  
    var start = $("#datepicker-range-start");
    var end = $("#datepicker-range-end");
  
    function days_diff(s, e) {
      var diff = new Date(e - s);
      var days = diff / 1000 / 60 / 60 / 24 + 1;
      return isNaN(days) ? 1 : days;
    }
  
    function today(number) {
      var today = new Date();
      var dd = today.getDate() + number;
      var mm = today.getMonth() + 1; //January is 0!
      var yyyy = today.getFullYear();
      if (dd < 10) {
        dd = "0" + dd;
      }
      if (mm < 10) {
        mm = "0" + mm;
      }
      return yyyy + "-" + mm + "-" + dd;
    }
  
    start.datepicker("update", today(0)),
      end.datepicker("update", today(1)),
      start.on("changeDate", function() {
        $("#start_date").val($(this).datepicker("getFormattedDate"));
        $("#deduction").val(
          days_diff(start.datepicker("getUTCDate"), end.datepicker("getUTCDate"))
        );
      }),
      end.on("changeDate", function() {
        $("#end_date").val($(this).datepicker("getFormattedDate"));
        $("#deduction").val(
          days_diff(start.datepicker("getUTCDate"), end.datepicker("getUTCDate"))
        );
      });
  }),
  
  (DaterangePickerPlugins = (function() {
    "use strict";
    return {
      init: function() {
        handleDateRangePicker();
      }
    };
  })());
  
  </script>