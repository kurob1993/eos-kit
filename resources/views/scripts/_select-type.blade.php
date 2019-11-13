<script type="text/javascript">
  (handleFilter = function () {
    // mengisi div.toolbar dengan form yang berisi select-filter

    
    // mengisi div.barperiod dengan form yang berisi period-filter
    $("div.typeperiod").append('{!! Form::open(["id" => "type-form"]) !!} {!! Form::close() !!}');
    $("#type-form").append('{!! Form::select("tipe", ["THR" => "THR","GAJI13" => "GAJI13","GAJI" => "GAJI", "APRESIASI" => "APRESIASI", "BONUS"=>"BONUS"], null, ["class"=>"form-control ml-5", "id"=>"type-filter"]) !!}');

    //$("div.yearperiod").append('{!! Form::open(["id" => "year-form"]) !!} {!! Form::close() !!}');
    //$("#year-form").append('{!! Form::selectMonth("month",null,["class"=>"form-control ml-5","id"=>"year-filter"] ) !!}');
    
    // registrasi change handler untuk period-filter
    $('#type-filter').change(function () {
      // simpan state select di dalam local storage 
      localStorage.setItem('typeSelect', this.value);
      // submit filter-form
      $("#type-form").submit();
    });

    // registrasi submit handler untuk period-form
    $("#type-form").on('submit', function (e) {
      // re draw datatables (reload konten tabel)
      window.LaravelDataTables["dataTableBuilder"].draw();
      // batalkan submit
      e.preventDefault();
    });

    // jika tersimpan state select-filter
    if (localStorage.getItem('typeSelect')) {
      // atur selected untuk select-filter
      $('#type-filter').val(localStorage.getItem('typeSelect'));
    }

    // mengisi div.year dengan form yang berisi select-filter
    $("div.yearperiod").html('<form method="post" id="year-form"> ' +
      '<select id="year-filter" class="form-control"> ' +
      @foreach($foundYear as $key=>$year)
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