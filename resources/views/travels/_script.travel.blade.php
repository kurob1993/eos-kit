<script type="text/javascript">
  (handleSelectpicker = function() {
  // fungsi untuk menampilkan input lampiran
// jika durasi cuti lebih dari atau sama dengan 2 hari
function attachment() {
    var durasi = Number($('#deduction').val());
    if (durasi >= 2) {
        $('#div_lampiran').html(' @include("travels._lampiran") ');
    } else {
        $('#div_lampiran').html('');
    }
}

function boss() {
    var bossOptions = {
        persist: false,
        valueField: "personnel_no",
        labelField: "name",
        searchField: ["name", "personnel_no"],
        options: [],
        render: {
            item: function (item, escape) {
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
            option: function (item, escape) {
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

    var durasi = Number($('#deduction').val());
    var tujuan = $('#tujuan').val();
    if (durasi >= 3 || tujuan == 'luar') {
        var name = '{{ Auth::user()->employee->gmBossWithDelegation()->name }}';
        var personnel_no = '{{ Auth::user()->employee->gmBossWithDelegation()->personnel_no }}';
    } else {
        var name = '{{ Auth::user()->employee->minManagerBossWithDelegation()->name }}';
        var personnel_no = '{{ Auth::user()->employee->minManagerBossWithDelegation()->personnel_no }}';
    }

    var o = { name: name, personnel_no: personnel_no };
    var newOptions = [];
    newOptions.push(o);
    bossOptions.options = newOptions;
    var bossSelect = $(".boss-selectize").selectize(bossOptions);
    var selectize = bossSelect[0].selectize;
    selectize.setValue(personnel_no, false);
}

function managerGa() {
    var bossOptions = {
        persist: false,
        valueField: "personnel_no",
        labelField: "name",
        searchField: ["name", "personnel_no"],
        options: [],
        render: {
            item: function (item, escape) {
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
            option: function (item, escape) {
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

    var name = '{{ Auth::user()->employee->managerGa()->first()->name }}';
    var personnel_no = '{{ Auth::user()->employee->managerGa()->first()->personnel_no }}';

    var o = { name: name, personnel_no: personnel_no };
    var newOptions = [];
    newOptions.push(o);
    bossOptions.options = newOptions;
    var bossSelect = $(".ga-selectize").selectize(bossOptions);
    var selectize = bossSelect[0].selectize;
    selectize.setValue(personnel_no, false);
}

var start = $("#datepicker-range-start");
var end = $("#datepicker-range-end");
start.on("changeDate", function () {
    attachment();
    boss();
});

end.on("changeDate", function () {
    attachment();
    boss();
});



    
$('#tujuan').change(function (data) {
    if ($(this).val() == 'dalam') {
        $('#div_kota').html(' @include("travels._list_kota") ');
    } else {
        $('#div_kota').html(' @include("travels._kota") ');
    }
});


$('#kendaraan').change(function (data) {
    if ($(this).val() == 'dinas') {
        $('#div_jenis_kendaraan').html(' @include("travels._nopol") ');
        $('#div_manager_ga').html(' @include("travels._managerga") ');
        managerGa();
    } else {
        $('#div_jenis_kendaraan').html(' @include("travels._jenis_kendaraan") ');
        $('#div_manager_ga').html('');
    }
});

$('#tujuan').change(function (data) {
    boss();
});
  
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