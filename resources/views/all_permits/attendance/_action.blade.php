{!! Form::model($model, ['url' => $confirm_url, 'method' => 'post', 
    'class' => 'visible-lg-inline js-confirm', 'data-confirm' => 'Apakah data cuti ini sudah ada di SAP?' ] ) !!}
{!! Form::button('<i class="fa fa-check"></i>', ['class'=>'btn btn-xs btn-info m-b-5']) !!}
{!! Form::close()!!}

{!! Form::model($model, ['url' => $integrate_url, 'method' => 'post', 
    'class' => 'visible-lg-inline js-confirm', 'data-confirm' => 'Apakah yakin akan mengirim data cuti ke SAP?' ] ) !!}
{!! Form::button('<i class="fa fa-flash"></i>', ['class'=>'btn btn-xs btn-danger m-b-5']) !!}
{!! Form::close()!!}