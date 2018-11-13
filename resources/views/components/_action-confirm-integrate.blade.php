{!! Form::model($model, [
        'url' => $confirm_url,
        'method' => 'post',
        'class' => 'visible-lg-inline js-confirm',
        'data-confirm' => 'Apakah data pengajuan ini sudah ada di SAP?',
        'id' => 'confirm'
    ])
!!}

{!! Form::button('<i class="fa fa-check js-button-submit"></i>', [
        'class' => 'btn btn-xs btn-info m-b-5',
        'data-actiontype' => 'confirm'
    ]) 
!!}

{!! Form::close()!!}

{!! Form::model($model, [
        'url' => $integrate_url,
        'method' => 'post', 
        'class' => 'visible-lg-inline js-confirm',
        'data-confirm' => 'Apakah yakin akan mengirim data pengajuan ke SAP?',
        'id' => 'integrate'
    ]) 
!!}

{!! Form::button('<i class="fa fa-flash js-button-submit"></i>', [
        'class'=>'btn btn-xs btn-danger m-b-5',
        'data-actiontype' => 'integrate'
    ])
!!}

{!! Form::close()!!}