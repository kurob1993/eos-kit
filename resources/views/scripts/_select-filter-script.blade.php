<script type="text/javascript">
  (handleFilter = function () {
    // mengisi div.toolbar dengan form yang berisi select-filter
    $("div.toolbar").html('<form method="post" id="filter-form"> ' +
      '<select id="select-filter" class="form-control"> ' +
      '<option selected>All</option>' +
      @foreach($stages as $stage)
      '<option value="{{ $stage->id }}">{{ $stage->description }}</option>' +
      @endforeach
    '</select>');

    // registrasi change handler untuk select-filter
    $('#select-filter').change(function () {
      // simpan state select di dalam local storage 
      localStorage.setItem('selectState', this.value);
      // submit filter-form
      $("#filter-form").submit();
    });
    
    // registrasi submit handler untuk filter-form
    $("#filter-form").on('submit', function (e) {
      // re draw datatables (reload konten tabel)
      window.LaravelDataTables["dataTableBuilder"].draw();
      // batalkan submit
      e.preventDefault();
    });

    // jika tersimpan state select-filter
    if (localStorage.getItem('selectState')) {
      // atur selected untuk select-filter
      $('#select-filter').val(localStorage.getItem('selectState'));
      // submit filter-form
      $("#filter-form").submit();
    }
  });

  (FilterPlugins = (function () {
    "use strict";
    return {
      init: function () {
        handleFilter();
      }
    };
  })());
</script>