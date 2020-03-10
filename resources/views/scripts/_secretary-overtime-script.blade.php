<script>
  (handleInlineDatePicker = function () {
      "use strict";
      $("#datepicker-inline").datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        startDate: {{ $start_date }},
        endDate: {{ $end_date }},
        // datesDisabled: ['2018-09-01'],
      });
      $('#datepicker-inline').on('changeDate', function () {
          $('#start_date').val(
              $('#datepicker-inline').datepicker('getFormattedDate')
          );
      });
    }),

    (handleTimePicker = function () {
        "use strict";
        $("#from, #to").timepicker({ })
    }),

    (handleSelectpicker = function() {

      // ==== select option min manager boss
      var bossCostCenter = function(value){
        var bossCostCenter = {
          persist: false,
          valueField: "personnel_no",
          labelField: "name",
          searchField: ["name", "personnel_no"],
          options: [ ],
          render: {
            item: function(item, escape) {
              return ( "<div>" + (item.personnel_no ? '<span class="label label-primary">' + escape(item.personnel_no) + "</span>&nbsp;" : "") + (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") + "</div>" );
            },
            option: function(item, escape) {
              var label = item.personnel_no || item.name;
              var caption = item.personnel_no ? item.name : null;
              return ( "<div>" + '<span class="label label-primary">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
            }
          },
        };

        $.ajax({
        url: '{{ url('api/costcenter') }}/' + value + '/boss',
            type: 'GET',
            dataType: 'json',
            error: function() {},
            success: function(res) {
              if ( window["managerSelectize"] === undefined ) {
                var bossSelect = $(".manager-selectize").selectize(bossCostCenter);
                window["managerSelectize"] = bossSelect[0].selectize;
              }
              window["managerSelectize"].clearOptions();
              var o = {name: res.name, personnel_no: res.personnel_no};
              window["managerSelectize"].addOption(o);
              window["managerSelectize"].setValue(res.personnel_no, false);
          }
        });

      }

      // ==== select option cost center
      var costCenterOptions = {
        onChange: bossCostCenter,
        persist: false,
        valueField: "id",
        labelField: "description",
        searchField: ["code", "description"],
        options: [ ],
        render: {
          item: function(item, escape) {
            return ( "<div>" + (item.code ? '<span class="label label-primary">' + escape(item.code) + "</span>&nbsp;" : "") + (item.description ? '<span class="name">' + escape(item.description) + "</span>" : "") + "</div>" );
          },
          option: function(item, escape) {
            var label = item.code || item.description;
            var caption = item.code ? item.description : null;
            return ( "<div>" + '<span class="label label-primary">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
          }
        },
      };

      $.ajax({
      url: '{{ url('api/costcenter') }}',
        type: 'GET',
        dataType: 'json',
        error: function() {},
        success: function(res) {
          var newOptions = [];
          for (var key in res) {
            var o = {id: res[key].id, code: res[key].code, description: res[key].description};
            newOptions.push(o);
          }
          costCenterOptions.options = newOptions;
          var bossSelect = $(".superintendent-selectize").selectize(costCenterOptions);
        }
      });

      // ==== select option list karyawan yang bisa mendapatkan lembur
      var subOptions = {
        persist: false,
        valueField: "personnel_no",
        labelField: "name",
        searchField: ["personnel_no", "name"],
        options: [    ],
        render: {
          item: function(item, escape) {
            return (
              "<div>" +
              (item.personnel_no ? '<span class="label label-primary">' + escape(item.personnel_no) + "</span>&nbsp;" : "") +
              (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") +
              "</div>"
            );
          },
          option: function(item, escape) {
            var label = item.personnel_no || item.name
            ;
            var caption = item.personnel_no ? item.name : null;
            return (
              "<div>" + '<span class="label label-primary">' + escape(label) + "</span>&nbsp;" +
              (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") +
              "</div>"
            );
          }
        }
      };

      $.ajax({
      url: '{{ url('api/structdisp') }}/{{ $user }}/foremanAndOperatorSubordinates',
          type: 'GET',
          dataType: 'json',
          error: function() {},
          success: function(res) {
            var newOptions = [];
            for (var key in res) {
              var o = {name: res[key].name, personnel_no: res[key].personnel_no};
              newOptions.push(o);
            }
            subOptions.options = newOptions;
            var subSelect = $(".sub-selectize").selectize(subOptions);
            var selectize = subSelect[0].selectize;
        }
      });

    }),

    (SecretaryOvertimePlugins = (function() {
      "use strict";
      return {
        init: function() {
          handleInlineDatePicker(), handleTimePicker(), handleSelectpicker();
        }
      };
    })());

</script>