<script>
    var startDate=null;
		var endDate=null;
		$(document).ready(function(){
			$('#start_date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: 'bottom',
      }).on('changeDate', function(ev){
					startDate=new Date(ev.date.getFullYear(),ev.date.getMonth(),ev.date.getDate(),0,0,0);
					if(endDate!=null&&endDate!='undefined'){
						if(endDate<startDate){
								// alert("End Date is less than Start Date");
								alert("Rentang tanggal periode tidak valid");
								$("#start_date").val("");
						}
					}
				});
			$("#end_date").datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        orientation: 'bottom',
      }).on("changeDate", function(ev){
					endDate=new Date(ev.date.getFullYear(),ev.date.getMonth(),ev.date.getDate(),0,0,0);
					if(startDate!=null&&startDate!='undefined'){
						if(endDate<startDate){
							// alert("End Date is less than Start Date");
							alert("Rentang tanggal periode tidak valid");
							$("#end_date").val("");
						}
					}
				});
		});
  </script>