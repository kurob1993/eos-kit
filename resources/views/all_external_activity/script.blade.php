var selectedMonth = localStorage.getItem("ActivitySelectMonth");
var selectedYear = localStorage.getItem("ActivitySelectYear");
var selectedStage = localStorage.getItem("ActivitySelectStage");

$('#month').val(selectedMonth);
$('#year').val(selectedYear);
$('#stage').val(selectedStage);

var cari = selectedMonth + '|' + selectedYear + '|' + selectedStage;
oTable = $('.table').DataTable();
oTable.search(cari).draw();

$('#export_month').val(selectedMonth);
$('#export_year').val(selectedYear);
$('#export_stage').val(selectedStage);
