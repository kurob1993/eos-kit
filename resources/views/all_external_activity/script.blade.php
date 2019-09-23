var m = localStorage.getItem("ActivitySelectMonth");
var y = localStorage.getItem("ActivitySelectYear");
var s = localStorage.getItem("ActivitySelectStage");
var selectedMonth = m ? m : '';
var selectedYear = y ? y : '';
var selectedStage = s ? s: '';

$('#month').val(selectedMonth);
$('#year').val(selectedYear);
$('#stage').val(selectedStage);

var cari = selectedMonth + '|' + selectedYear + '|' + selectedStage;
oTable = $('.table').DataTable();
oTable.search(cari).draw();

$('#export_month').val(selectedMonth);
$('#export_year').val(selectedYear);
$('#export_stage').val(selectedStage);