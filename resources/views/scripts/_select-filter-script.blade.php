<script type="text/javascript">
	(handleFilter = function(){
        $("div.toolbar").html('<form method="post" id="filter-form"> ' +
          '<select id="select-filter" class="form-control"> ' +
            @foreach ($stages as $stage)
            '<option value="{{ $stage->id }}">{{ $stage->description }}</option>' +
            @endforeach
          '</select>');
        $('#select-filter').on('change', function() {
            $("#filter-form").submit();
        });        
        $("#filter-form").on('submit', function(e) {
            window.LaravelDataTables["dataTableBuilder"].draw();
            e.preventDefault();
        });
	});

	(FilterPlugins = (function() {
	  "use strict";
	  return {
	    init: function() {
	      handleFilter();
	    }
	  };
	})());
</script>