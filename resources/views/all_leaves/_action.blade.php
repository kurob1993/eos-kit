{!! Form::model($model, ['url' => $integrate_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => 'Apakah yakin akan mengirim data cuti ke SAP?' ] ) !!}
{!! Form::submit('Integrasi', ['class'=>'btn btn-xs btn-primary m-b-5']) !!}
{!! Form::close()!!}

{!! Form::model($model, ['url' => $confirm_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => 'Apakah data cuti ini sudah ada di SAP?' ] ) !!}
{!! Form::submit('Konfirmasi', ['class'=>'btn btn-xs btn-danger m-b-5']) !!}
{!! Form::close()!!}