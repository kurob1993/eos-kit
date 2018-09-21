<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $leaveId }}">
    <tbody>
        <tr>
            <td>Mulai</td>
            <td> {{ $leave->start_date->formatted_start_date }} </td>
        </tr>
        <tr>
            <td>Berakhir</td>
            <td> {{ $leave->end_date->formatted_end_date }} </td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td>{{ $leave->duration }} hari</td>
        </tr>
        <tr>
            <td>Jenis Cuti</td>
            <td>{{ $leave->absenceType->text }}</td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>{{ $leave->note }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>{{ $leave->address }}</td>
        </tr>
        <tr>
            <td>Atasan</td>
            <td>{{ $leave->absenceApprovals->first()->user->personnelNoWithName }}</td>
        </tr>
        <tr>
            <td>Tahap</td>
            <td><span class="label label-default">{{ $leave->stage->description }}</span></td>
        </tr>
    </tbody>
</table>
<br />
{!! Form::model($leave, ['url' => $approve_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'persetujuan?'] ) !!}
{!! Form::submit('Setuju', ['class'=>'btn btn-block btn-primary m-b-5']) !!}
{!! Form::close()!!}
{!! Form::model($leave, ['url' => $reject_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'penolakan?'] ) !!}
{!! Form::submit('Tolak', ['class'=>'btn btn-block btn-danger m-b-5']) !!}
{!! Form::close()!!}

