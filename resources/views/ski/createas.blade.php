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

<style>
table {
  border-collapse: collapse;
}

table, th, td {
  border: 1px solid black;
}
</style>

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
  function keyPress() {
    $(document).keydown(function(e){
        e = e || window.event;
        var keyCode = e.keyCode || e.which;
        if(keyCode=='13' || e.which == 40){ //arrow key
            var id = e.target.id;            
            id = id.split("_");
            
            id = id[0]+'_'+ (Number(id[1])+1);

            $('#'+id).focus();
            
            e.preventDefault();
            return false;
        }    

        if(e.which == 38){ //arrow key
            var id = e.target.id;
            id = id.split("_");
            
            id = id[0]+'_'+ (Number(id[1])-1);

            $('#'+id).focus();
            
            e.preventDefault();
            return false;
        }

    });

  }
  
  function setAutoComplete() {
    $('input').attr('autocomplete','off');
  }

  function numberOnly(value, id) {
    value = value.replace(/[^0-9\.]/g,'');
    $('#'+id).val(value);
  }

  // share kpi
  function setNilaiShareKpi() {
    var bobot = $('#bobotShareKpi').val();
    var skor = $('#skorShareKpi').val();
    var nilai = (skor*bobot)/10;
    $('#nilaiShareKpi').val(nilai);
  }

  // kpi hasil
  function CekBobotKpiHasil(id) {
    var count = Number( $('#last_id_kpi_hasil').val() );
    var sum = 0;
    for (let index = 0; index <= count; index++) {
      var bobot = Number( $('#bobotKpiHasil_'+index).val() );
      sum +=bobot;
    }
    if(sum > 65){
      alert('Bobot tidak boleh lebih dari 65%');
      bobot = Number( $('#'+id).val() );
      sum = sum - bobot;
      $('#'+id).val('');
      // console.log(id);
    }
    $('#totalBobotKpiHasil').val(sum);
  }

  function setNilaiKpiHasil(id) {
    var count = Number( $('#last_id_kpi_hasil').val() );
    var sum = 0;
    for (let index = 0; index <= count; index++) {
      var capaian = Number( $('#capaianKpiHasil_'+index).val() );
      var bobot = Number( $('#bobotKpiHasil_'+index).val() );
      var nilai = (bobot*capaian)/10;
      $('#nilaiKpiHasil_'+index).val(nilai)
      sum +=nilai;
    }
    
    $('#totalNilaiKpiHasil').val(sum);
  }
</script>
@endpush

@push('on-ready-scripts') 
SecretaryOvertimePlugins.init();
setAutoComplete();
keyPress();
@endpush
