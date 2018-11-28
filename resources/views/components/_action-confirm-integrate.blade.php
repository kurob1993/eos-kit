{!! Form::model($model, [
        'url' => $confirm_url,
        'method' => 'post',
        'class' => 'visible-lg-inline js-confirm',
        'data-confirm' => 'Apakah data pengajuan ini sudah ada di SAP?',
        'id' => 'confirm-' . $model->id
    ])
!!}

{!! Form::button('<i class="fa fa-check js-button-submit"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-xs btn-info m-b-5',
        'data-actiontype' => 'confirm-' . $model->id
    ]) 
!!}

{!! Form::close()!!}

{!! Form::model($model, [
        'url' => $integrate_url,
        'method' => 'post', 
        'class' => 'visible-lg-inline js-confirm',
        'data-confirm' => 'Apakah yakin akan mengirim data pengajuan ke SAP?',
        'id' => 'integrate-' . $model->id
    ]) 
!!}

{!! Form::button('<i class="fa fa-flash js-button-submit"></i>', [
        'type' => 'submit',
        'class'=>'btn btn-xs btn-danger m-b-5',
        'data-actiontype' => 'integrate-' . $model->id
    ])
!!}

{!! Form::close()!!}