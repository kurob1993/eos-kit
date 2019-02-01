<script type="text/javascript">
  (handleFilter = function () {
    // mengisi div.toolbar dengan form yang berisi select-filter
    $("div.toolbar").html('<form method="post" id="filter-form"> ' +
      '<select id="select-filter" class="form-control"> ' +
      '<option selected>All</option>' +
      @foreach($stages as $stage)
      '<option value="{{ $stage->id }}">{{ $stage->description }}</option>' +
      @endforeach
    '</select></form>');

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
    }

    // mengisi div.barperiod dengan form yang berisi period-filter
    $("div.monthperiod").append('{!! Form::open(["id" => "month-form"]) !!} {!! Form::close() !!}');
    $("#month-form").append('{!! Form::selectMonth("month",1,["class"=>"form-control ml-5","id"=>"month-filter"] ) !!}');

    //$("div.yearperiod").append('{!! Form::open(["id" => "year-form"]) !!} {!! Form::close() !!}');
    //$("#year-form").append('{!! Form::selectMonth("month",null,["class"=>"form-control ml-5","id"=>"year-filter"] ) !!}');
    
    // registrasi change handler untuk period-filter
    $('#month-filter').change(function () {
      // simpan state select di dalam local storage 
      localStorage.setItem('monthSelect', this.value);
      // submit filter-form
      $("#month-form").submit();
    });

    // registrasi submit handler untuk period-form
    $("#month-form").on('submit', function (e) {
      // re draw datatables (reload konten tabel)
      window.LaravelDataTables["dataTableBuilder"].draw();
      // batalkan submit
      e.preventDefault();
    });

    // jika tersimpan state select-filter
    if (localStorage.getItem('monthSelect')) {
      // atur selected untuk select-filter
      $('#month-filter').val(localStorage.getItem('monthSelect'));
    }

    // mengisi div.year dengan form yang berisi select-filter
    $("div.yearperiod").html('<form method="post" id="year-form"> ' +
      '<select id="year-filter" class="form-control"> ' +
      @foreach($foundYears as $key=>$year)
        @if($key == 0)
        '<option selected value="{{ $year->year }}">{{ $year->year }}</option>' +
        @else
        '<option value="{{ $year->year }}">{{ $year->year }}</option>' +
        @endif
      @endforeach
      
    '</select></form>');

    // registrasi change handler untuk select-filter
    $('#year-filter').change(function () {
      // simpan state select di dalam local storage 
      localStorage.setItem('yearSelect', this.value);
      // submit filter-form
      $("#year-form").submit();
    });
    
    // registrasi submit handler untuk filter-form
    $("#year-form").on('submit', function (e) {
      // re draw datatables (reload konten tabel)
      window.LaravelDataTables["dataTableBuilder"].draw();
      // batalkan submit
      e.preventDefault();
    });

    // jika tersimpan state select-filter
    if (localStorage.getItem('yearSelect')) {
      // atur selected untuk select-filter
      $('#year-filter').val(localStorage.getItem('yearSelect'));
    }
    $("#year-form").submit();
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