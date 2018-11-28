<script>
(handleInlineDatePicker) = function() {
    "use strict";
    $("#datepicker-inline").datepicker({ 
      format: 'yyyy-mm-dd',
      todayHighlight: true,
      startDate: {{ $start_date}},
      endDate: {{ $end_date}},
      // datesDisabled: ['2018-09-01'],
     });
    $('#datepicker-inline').on('changeDate', function() {
      $('#check_date').val(
          $('#datepicker-inline').datepicker('getFormattedDate')
      );
    });    
  },
  (handleTimePicker) = function() {
    "use strict";
    $("#timepicker").timepicker({ })
  },

    (InlineDatepickerPlugins = (function() {
    "use strict";
    return {
      init: function() {
        handleInlineDatePicker(),handleTimePicker();
      }
    };
  })());
  </script>