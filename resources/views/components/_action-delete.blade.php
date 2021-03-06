{!! Form::model($model, [
    'url' => $delete_url,
    'method' => 'post', 
    'class' => 'visible-lg-inline js-confirm',
    'data-confirm' => 'Yakin akan menghapus data pengajuan ini?',
    'id' => 'delete-' . $model->id
])
!!}

{!! Form::button('<i class="fa fa-times js-button-submit"></i>', [
    'type' => 'submit',
    'title' => 'Hapus',
    'class' => 'btn btn-xs btn-danger m-b-5',
    'title' => 'Hapus',
    'data-actiontype' => 'delete-' . $model->id
])
!!}

{!! Form::close()!!}