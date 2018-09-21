<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $leaveId }}">
    <tbody>
        <tr>
            <td>Mulai</td>
            <td> {{ $leave->formatted_start_date }} </td>
        </tr>
        <tr>
            <td>Berakhir</td>
            <td> {{ $leave->formatted_start_date }} </td>
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
            <td>Tahap</td>
            <td><span class="label label-default">{{ $leave->stage->description }}</span></td>
        </tr>
        @if ($leave->is_sent_to_sap) 
        <tr>
            <td>Disetujui oleh:</td>
            <td>
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $leave->absenceApprovals->first()->user->personnel_no,
                    'employee_name' => $leave->absenceApprovals->first()->user->name])
                @endcomponent 
            </td>
        </tr>
        <tr>
            <td>Disetujui pada:</td>
            <td>{{ $leave->updated_at->format('d.m.Y - H:i:s') }} </td>
        </tr>
        @else 
        <tr>
            <td>Atasan:</td>
            <td>
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $leave->absenceApprovals->first()->user->personnel_no,
                    'employee_name' => $leave->absenceApprovals->first()->user->name
                ])
                @endcomponent
            </td>
        </tr>
        @endif 
    </tbody>
</table>
<br />
@if (!$leave->is_sent_to_sap)
{!! Form::model($leave, ['url' => $approve_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'persetujuan?'] ) !!}
{!! Form::submit('Setuju', ['class'=>'btn btn-block btn-primary m-b-5']) !!}
{!! Form::close()!!}
{!! Form::model($leave, ['url' => $reject_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'penolakan?'] ) !!}
{!! Form::submit('Tolak', ['class'=>'btn btn-block btn-danger m-b-5']) !!}
{!! Form::close()!!}
@endif