<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$(document).ajaxSuccess(function() {
    console.log( "Triggered ajaxSuccess handler." );
  });
</script>