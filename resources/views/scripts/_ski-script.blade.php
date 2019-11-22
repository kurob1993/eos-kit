<script>
    (handleSelectpicker = function() {

      $('#personnel_no').on('change',function(e) {
        var valuedata = $(this).val();
        var textdata = $(this).text();
        localStorage.setItem('empNikSelect', valuedata);
        localStorage.setItem('empNamaSelect', textdata);
      });

      $('.manager-selectize').on('change',function(e) {
        var valuedata = $(this).val();
        var textdata = $(this).text();
        localStorage.setItem('manNikSelect', valuedata);
        localStorage.setItem('manNamaSelect', textdata);
      });

      $('.superintendent-selectize').on('change',function(e) {
        var valuedata = $(this).val();
        var textdata = $(this).text();
        localStorage.setItem('supNikSelect', valuedata);
        localStorage.setItem('supNamaSelect', textdata);
      });

      var localEmpNikSelect = localStorage.getItem('empNikSelect');
      var localEmpNamaSelect = localStorage.getItem('empNamaSelect');

      var localManNikSelect = localStorage.getItem('manNikSelect');
      var localManNamaSelect = localStorage.getItem('manNamaSelect');

      var localSupNikSelect = localStorage.getItem('supNikSelect');
      var localSupNamaSelect = localStorage.getItem('supNameSelect');

      if(localStorage.getItem('empNikSelect')) {
        $('#btn-sasaran').removeAttr('style');
        $('#btn-perilaku').removeAttr('style');
      }
      var subOptions = {
        onChange: function(value) {
          $('#btn-sasaran').removeAttr('style');
          $('#btn-perilaku').removeAttr('style');
          var minManagerOptions = {
            persist: false,
            valueField: "personnel_no",
            labelField: "name",
            searchField: ["name", "personnel_no"],
            items: [localManNikSelect, localManNamaSelect],
            options: [ ],
            render: {
              item: function(item, escape) {
                return ( "<div>" + (item.personnel_no ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;" : "") + (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") + "</div>" );
              },
              option: function(item, escape) {
                var label = item.personnel_no || item.name;
                var caption = item.personnel_no ? item.name : null;
                return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
              }
            },
          };

          var minSuperintendentOptions = {
            persist: false,
            valueField: "personnel_no",
            labelField: "name",
            searchField: ["name", "personnel_no"],
            items: [localSupNikSelect, localSupNamaSelect],
            options: [ ],
            render: {
              item: function(item, escape) {
                return ( "<div>" + (item.personnel_no ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;" : "") + (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") + "</div>" );
              },
              option: function(item, escape) {
                var label = item.personnel_no || item.name;
                var caption = item.personnel_no ? item.name : null;
                return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
              }
            },
          };

          $.ajax({
          url: '{{ url('api/structdisp') }}/' + value + '/minManagerBossWithDelegation',
              type: 'GET',
              dataType: 'json',
              error: function() {},
              success: function(res) {
                if ( window["managerSelectize"] === undefined ) {
                  var bossSelect = $(".manager-selectize").selectize(minManagerOptions);
                  window["managerSelectize"] = bossSelect[0].selectize;
                }
                window["managerSelectize"].clearOptions();
                var o = {name: res.name, personnel_no: res.personnel_no};
                window["managerSelectize"].addOption(o);
                window["managerSelectize"].setValue(res.personnel_no, false);
            }
          });

          $.ajax({
          url: '{{ url('api/structdisp') }}/' + value + '/minSptBossWithDelegation',
              type: 'GET',
              dataType: 'json',
              error: function() {},
              success: function(res) {
                if ( window["superintendentSelectize"] === undefined ) {
                  var bossSelect = $(".superintendent-selectize").selectize(minSuperintendentOptions);
                  window["superintendentSelectize"] = bossSelect[0].selectize;
                }
                window["superintendentSelectize"].clearOptions();
                var o = {name: res.name, personnel_no: res.personnel_no};
                window["superintendentSelectize"].addOption(o);
                window["superintendentSelectize"].setValue(res.personnel_no, false);
            }
          });

        },
        persist: false,
        valueField: "personnel_no",
        labelField: "name",
        searchField: ["personnel_no", "name"],
        items:[localEmpNikSelect,localEmpNamaSelect],
        options: [    ],
        render: {
          item: function(item, escape) {
            return (
              "<div>" +
              (item.personnel_no ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;" : "") +
              (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") +
              "</div>"
            );
          },
          option: function(item, escape) {
            var label = item.personnel_no || item.name
            ;
            var caption = item.personnel_no ? item.name : null;
            return (
              "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" +
              (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") +
              "</div>"
            );
          }
        }
      };
      
      $.ajax({
      url: '{{ url('api/structdisp') }}/{{ $user }}/subordinates',
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

      var minManagerOptions1 = {
          persist: false,
          valueField: "personnel_no",
          labelField: "name",
          searchField: ["name", "personnel_no"],
          items: [localManNikSelect, localManNamaSelect],
          options: [ ],
          render: {
            item: function(item, escape) {
              return ( "<div>" + (item.personnel_no ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;" : "") + (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") + "</div>" );
            },
            option: function(item, escape) {
              var label = item.personnel_no || item.name;
              var caption = item.personnel_no ? item.name : null;
              return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
            }
          },
        };

        var minSuperintendentOptions1 = {
          persist: false,
          valueField: "personnel_no",
          labelField: "name",
          searchField: ["name", "personnel_no"],
          items: [localSupNikSelect, localSupNamaSelect],
          options: [ ],
          render: {
            item: function(item, escape) {
              return ( "<div>" + (item.personnel_no ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;" : "") + (item.name ? '<span class="name">' + escape(item.name) + "</span>" : "") + "</div>" );
            },
            option: function(item, escape) {
              var label = item.personnel_no || item.name;
              var caption = item.personnel_no ? item.name : null;
              return ( "<div>" + '<span class="label label-default">' + escape(label) + "</span>&nbsp;" + (caption ? '<span class="caption">' + escape(caption) + "</span>" : "") + "</div>" );
            }
          },
        };

      if(localStorage.getItem('empNikSelect')) {
        $.ajax({
        url: '{{ url('api/structdisp') }}/' + localEmpNikSelect + '/minManagerBossWithDelegation',
            type: 'GET',
            dataType: 'json',
            error: function() {},
            success: function(res) {
              if ( window["managerSelectize"] === undefined ) {
                var bossSelect = $(".manager-selectize").selectize(minManagerOptions1);
                window["managerSelectize"] = bossSelect[0].selectize;
              }
              window["managerSelectize"].clearOptions();
              var o = {name: res.name, personnel_no: res.personnel_no};
              window["managerSelectize"].addOption(o);
              window["managerSelectize"].setValue(res.personnel_no, false);
          }
        });
      }

      if(localStorage.getItem('manNikSelect')) {
        $.ajax({
        url: '{{ url('api/structdisp') }}/' + localEmpNikSelect + '/minSptBossWithDelegation',
            type: 'GET',
            dataType: 'json',
            error: function() {},
            success: function(res) {
              if ( window["superintendentSelectize"] === undefined ) {
                var bossSelect = $(".superintendent-selectize").selectize(minSuperintendentOptions1);
                window["superintendentSelectize"] = bossSelect[0].selectize;
              }
              window["superintendentSelectize"].clearOptions();
              var o = {name: res.name, personnel_no: res.personnel_no};
              window["superintendentSelectize"].addOption(o);
              window["superintendentSelectize"].setValue(res.personnel_no, false);
          }
        });
      }
    }),

    (SecretaryOvertimePlugins = (function() {
      "use strict";
      return {
        init: function() {
          handleSelectpicker();
        }
      };
    })());

</script>