{!! Form::model($model, ['url' => $delete_url, 'method' => 'post', 
    'class' => 'visible-lg-inline js-confirm', 'data-confirm' => 'Yakin akan menghapus data pengajuan ini?' ] ) !!}
{!! Form::button('<i class="fa fa-times js-button-submit"></i>', ['class'=>'btn btn-xs btn-danger m-b-5']) !!}
{!! Form::close()!!}