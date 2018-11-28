<script type="text/javascript">
  (handleSelectpicker = function() {
    var bossOptions = {
      persist: false,
      valueField: "personnel_no",
      labelField: "name",
      searchField: ["name", "personnel_no"],
      options: [ ],
      render: {
        item: function(item, escape) {
          return (
            "<div>" +
            (item.personnel_no
              ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;"
              : "") +
            (item.name
              ? '<span class="name">' + escape(item.name) + "</span>"
              : "") +
            "</div>"
          );
        },
        option: function(item, escape) {
          var label = item.personnel_no || item.name;
          var caption = item.personnel_no ? item.name : null;
          return (
            "<div>" +
            '<span class="label label-default">' +
            escape(label) +
            "</span>&nbsp;" +
            (caption
              ? '<span class="caption">' + escape(caption) + "</span>"
              : "") +
            "</div>"
          );
        }
      },
    };
  
    $.ajax({
    url: '{{ url('api/structdisp') }}/{{ Auth::user()->personnel_no}}/minSuperintendentBoss',
        type: 'GET',
        dataType: 'json',
        error: function() {},
        success: function(res) {
          var newOptions = [];
          var o = {name: res.name, personnel_no: res.personnel_no};
          newOptions.push(o);
          bossOptions.options = newOptions;
          var bossSelect = $(".boss-selectize").selectize(bossOptions);
          var selectize = bossSelect[0].selectize;
          selectize.setValue(res.personnel_no, false);
      }
    });
    
     @if (Auth::user()->employee()->first()->canDelegate())
    
    var subOptions = {
      persist: false,
      valueField: "name",
      labelField: "personnel_no",
      searchField: ["personnel_no", "name"],
      options: [    ],
      render: {
        item: function(item, escape) {
          return (
            "<div>" +
            (item.personnel_no
              ? '<span class="label label-default">' + escape(item.personnel_no) + "</span>&nbsp;"
              : "") +
            (item.name
              ? '<span class="name">' + escape(item.name) + "</span>"
              : "") +
            "</div>"
          );
        },
        option: function(item, escape) {
          var label = item.personnel_no || item.name;
          var caption = item.personnel_no ? item.name : null;
          return (
            "<div>" +
            '<span class="label label-default">' +
            escape(label) +
            "</span>&nbsp;" +
            (caption
              ? '<span class="caption">' + escape(caption) + "</span>"
              : "") +
            "</div>"
          );
        }
      }
    };
  
    $.ajax({
    url: '{{ url('api/structdisp') }}/{{ Auth::user()->personnel_no}}/subordinates',
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
    
    @endif
  
  }),
  
  (StructdispSelectPlugins = (function() {
    "use strict";
    return {
      init: function() {
        handleSelectpicker();
      }
    };
  })());
  
  </script>