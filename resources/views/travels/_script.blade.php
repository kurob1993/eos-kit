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
    if (durasi >= 3) {
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
    selectize.setValue('123 123', false);
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