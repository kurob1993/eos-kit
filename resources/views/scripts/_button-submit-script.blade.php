<script type="text/javascript">
	(handleConfirm = function(){
		$('.dataTable tbody').on('click', ':button', function (e) {
			var actiontype = $(this).data('actiontype');
			$('form#'+actiontype).submit();
		});

		$('.dataTable tbody').on('submit', '.js-confirm', function (e) {
			var $el = $(this);
			var text = $el.data('confirm') ? 
			$el.data('confirm') : 'Anda yakin melakukan tindakan ini?'

			// tampilkan pop up konfirmasi
			var c = confirm(text);

			// kondisi konfirmasi terkait tindakan
			if (c) { return true; } else { e.preventDefault(); } 
		});
	});

	(ButtonSubmitPlugins = (function() {
	  "use strict";
	  return {
	    init: function() {
	      handleConfirm();
	    }
	  };
	})());
</script>