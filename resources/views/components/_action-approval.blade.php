{!! Form::model($model, [
    'url' => $approve_url,
    'method' => 'post', 
    'class' => 'js-confirm',
    'style' => 'display:inline-block',
    'data-confirm' => 'Yakin akan menyetujui data pengajuan ini?',
    'id' => $approval.'-approve-' . $model->id
])
!!}

{!! Form::button('<i class="fa fa-check js-button-submit"></i>', [
    'type' => 'submit',
    'class' => 'btn btn-xs btn-primary m-b-5',
    'data-actiontype' => $approval.'-approve-' . $model->id
])
!!}

{!! Form::close()!!}

{!! Form::model($model, [
    'url' => $reject_url,
    'method' => 'post', 
    'class' => 'js-confirm',
    'style' => 'display:inline-block',
    'data-confirm' => 'Yakin akan menolak data pengajuan ini?',
    'id' => $approval.'-delete-' . $model->id
])
!!}

{!! Form::button('<i class="fa fa-times js-button-submit"></i>', [
    'type' => 'submit',
    'class' => 'btn btn-xs btn-danger m-b-5',
    'data-actiontype' => $approval.'-delete-' . $model->id
])
!!}

{!! Form::close()!!}