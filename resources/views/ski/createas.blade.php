@extends('layouts.app') 

@section('content')

<!-- begin #page-container -->
@component($pageContainer, ['page_header' => 'Sasaran Kinerja Individu'])
<div class="row">
  <div class="col-lg-12 col-xl-9">
    @include('layouts._flash')
    <div class="panel panel-prussian">
      <div class="panel-heading">
        <h4 class="panel-title">Input Sasaran Kinerja Individu</h4>
      </div>
<div class="alert alert-success fade in">
          <i class="fa fa-book pull-left"></i>
          <p> Untuk pertanyaan & petunjuk penggunaan hubungi <b> Divisi Human Capital Integration & Adm.</b> telepon <b> 72163</b> </p>
          <br>          
      </div>
      <div class="panel-body">
        {!! Form::open([
            'url' => $formRoute, 
            'method' => 'post', 
            'class'=>'form-horizontal', 
            'id'=>'forms', 
            'data-parsley-validate' => 'true'
            ])
        !!}
        <input type="hidden" name="aksi" value="" id="aksi" />
        @include('ski._formas')
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>

@endcomponent
<!-- end page container -->

@endsection

@push('styles')
<!-- Datepicker -->
<link href={{ url("/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/bootstrap-datepicker/css/datepicker3.css") }} rel="stylesheet" />
<!-- Selectize -->    
<link href={{ url("/plugins/bootstrap-select/bootstrap-select.min.css") }} rel="stylesheet" />
<link href={{ url("/plugins/selectize/selectize.css") }} rel="stylesheet">
<link href={{ url("/plugins/selectize/selectize.bootstrap3.css") }} rel="stylesheet"> 
<!-- Parsley -->    
<link href={{ url("/plugins/parsley/src/parsley.css") }} rel="stylesheet" />
<!-- Timepicker -->    
<link href={{ url( "/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css") }} rel="stylesheet" />
<!-- Pace -->    
<script src={{ url("/plugins/pace/pace.min.js") }}></script>
@endpush

@push('plugin-scripts')
<script src={{ url("/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}></script>
<!-- Selectize -->    
<script src={{ url("/plugins/bootstrap-select/bootstrap-select.min.js") }}></script>
<script src={{ url("/plugins/selectize/selectize.min.js") }}></script>
<!-- Parsley --> 
<script src={{ url("/plugins/parsley/dist/parsley.js") }}></script>
<!-- Timepicker --> 
<script src={{ url( "/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js") }}></script>
@endpush 

@push('custom-scripts')
@include('scripts._ski-script',[
  'start_date' =>  config('emss.modules.time_events.start_date'),
  'end_date' =>  config('emss.modules.time_events.end_date')
])
<script>
  function ceking(isi) {
      $('#aksi').val(isi);
      return true;
  }

  function checkSkor(value, iddata) {
    if(value > 10) {
      alert('Nilai tidak boleh lebih dari 10 ');
      $('#skor'+iddata).val(0);
      $('#nilai'+iddata).val(0);
      e.preventDefault();
      return false;
    }
  }
  
  $(document).on("keypress", 'form', function (e) {
      var code = e.keyCode || e.which;
      if (code == 13) {
          e.preventDefault();
          return false;
      }
  });

  function setNilai(id) {
    var count = Number( $('#id').val() );
    var bobot = $('#bobot'+id).val();
    var skor = $('#skor'+id).val();
    var sum_perilaku = 0;
    var sum_kinerja = 0;
    var sum_nilai_kinerja1 = 0;
    var sum_nilai_perilaku1 = 0;
    
    

    $('#nilai'+id).val(bobot*skor);

    for (let index = 0; index < count; index++) {
      var klp = $('#klp'+index).val();
      var klpp = $('#klpp'+index).val();
      var bobot =  Number( $('#bobot'+index).val() );
      var skor =  Number( $('#skor'+index).val() );
      

      if(klpp == 'Perilaku'){
        sum_perilaku += bobot;
        $('#sum_perilaku').val(sum_perilaku);
        sum_nilai_perilaku1 += bobot*skor;
        $('#sum_nilai_perilaku1').val(sum_nilai_perilaku1);
      }

      if(klp == 'Kinerja'){
        sum_kinerja += bobot;
        $('#sum_kinerja').val(sum_kinerja);
        $('#sum_kinerja1').val(sum_kinerja);
        sum_nilai_kinerja1 += bobot*skor;
        $('#sum_nilai_kinerja1').val(sum_nilai_kinerja1);
      }
      
    }
    var msg_perilaku = '';
    var msg_kinerja = '';
    // if(sum_perilaku < 100 && sum_perilaku !== 0){
    //   msg_perilaku = 'Bobot Perilaku Kurang dari 100';
    // }
    // if(sum_perilaku > 100 && sum_perilaku !== 0){
    //   msg_perilaku = 'Bobot Perilaku Lebih dari 100';
    // }
    // if(sum_kinerja < 100 && sum_kinerja !== 0){
    //   msg_kinerja = 'Bobot Kinerja Kurang dari 100';
    // }
    // if(sum_kinerja > 100 && sum_kinerja !== 0){
    //   msg_kinerja = 'Bobot Kinerja Lebih dari 100';
    // }
    // $('#bobot_perilaku').html(msg_perilaku);
    // $('#bobot_kinerja').html(msg_kinerja);

    // if(sum_kinerja == 100 && sum_perilaku == 100){
    //   $('#kirim').removeClass('hidden');
    // }else{
    //   $('#kirim').addClass('hidden');
    // }

    
    if(sum_kinerja < 100 && sum_kinerja !== 0){
      msg_kinerja = 'Bobot Kinerja Kurang dari 100';
    }
    if(sum_kinerja > 100 && sum_kinerja !== 0){
      msg_kinerja = 'Bobot Kinerja Lebih dari 100';
    }
    $('#bobot_perilaku').html(msg_perilaku);
    $('#bobot_kinerja').html(msg_kinerja);

    if(sum_kinerja == 100){
      $('#kirim').removeClass('hidden');
    }else{
      $('#kirim').addClass('hidden');
    }
  }

  function checkSkorPerilaku(value, iddata) {
    if(value > 10) {
      alert('Nilai tidak boleh lebih dari 10 ');
      $('#skorp'+iddata).val(0);
      $('#nilaip'+iddata).val(0);
      setNilaiPerilaku(iddata);
    }
  }

  function setNilaiPerilaku(id) {
    var count = Number( $('#idp').val() );
    var bobot = $('#bobotp'+id).val();
    var skor  = $('#skorp'+id).val();
    var sum_perilaku = 0;
    var sum_nilai_perilaku1 = 0;
    console.log(bobot +' - '+ skor);
    
    $('#nilaip'+id).val(bobot*skor);

    for (let index = 0; index < count; index++) {
      var klpp  = $('#klpp'+index).val();
      var bobot = Number( $('#bobotp'+index).val() );
      var skor  = Number( $('#skorp'+index).val() );

      sum_nilai_perilaku1 += bobot*skor;
      $('#sum_nilai_perilaku1').val(sum_nilai_perilaku1);
    }
  }

  function add_column() {
    var id = Number( $('#id').val() );
    var kolom = '<tr>'+
      '<td class="text-center">'+ (id+1) +'</td>'+
      '<td>'+
      '<input type="text" name="sasaran[]" style="width: 100%">'+
      '<select name="klp[]" style="width: 100%; height: 26px; display:none">'+
          '<option value="Kinerja">Kinerja</option>'+
        '</select> '+
      '</td>'+
      '<td><input type="text" name="kode[]" style="width: 100%"></td>'+
      '<td><input type="text" name="ukuran[]" style="width: 100%"></td>'+
      '<td> '+
        '<input type="text" '+
          'name="bobot[]" '+
          'id="bobot'+id+'" '+
          'style="width: 100%; text-align: right"'+
          'onkeyup="setNilai('+id+')"'+
        '> '+
      '</td>'+
      '<td> '+
        '<input type="text" '+
          'name="skor[]" '+
          'id="skor'+id+'" '+
          'style="width: 100%; text-align: right"'+
          'onkeyup="setNilai('+id+')"'+
        '>'+
      '</td>'+
      '<td>'+
        '<input type="text" '+
          'name="nilai[]" '+
          'id="nilai'+id+'" '+
          'style="width: 100%; text-align: right"'+
          'readonly'+
        '>'+
      '</td>'+
      '</tr>';
    $('#tbody').append(kolom);
    $('#id').val(id+1);
  }
</script>
@endpush

@push('on-ready-scripts') 
SecretaryOvertimePlugins.init();
@endpush
