<script type="text/javascript">
	(handleConfirm = function(){
		// registrasi click handler untuk button di tbody
		$('.dataTable tbody').on('click', ':button', function (e) {
			// menyimpan atribut actiontype dari button
			var actiontype = $(this).data('actiontype');
			
			// submit form+actiontype
			$('form#'+actiontype)
				.off('submit')
				.on('submit', function (e){

				// mengambil kalimat konfirmasi 
				var $el = $(this);
				var text = $el.data('confirm') ? 
				$el.data('confirm') : 'Anda yakin melakukan tindakan ini?'

				// tampilkan pop up konfirmasi
				var c = confirm(text);

				// kondisi konfirmasi terkait tindakan
				if (c) { return true; } else { e.preventDefault(); }
			});
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