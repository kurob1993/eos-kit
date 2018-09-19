<script type="text/javascript">
(handleModalDetail = function () {
  $('.table-striped tbody').on('click', 'tr', function () {
    var dataUrl = $(this).attr('data-href');
    $('.modal-body').load(dataUrl,function(){
        $('#modal-dialog').modal({show:true});
    });
  });

  $("#modal-dialog").on('show.bs.modal', function () {
    $('#title-span').text(
      $('#table-detail').attr('data-id')
    );
  });  
}),

(ModalDetailPlugins = (function() {
  "use strict";
  return {
    init: function() {
      handleModalDetail();
    }
  };
})());
</script>