<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $timeEvent->plain_id }}">
    <tbody>
        <tr>
            <td>Tanggal</td>
            <td> {{ $timeEvent->check_date->format('d.m.Y') }} </td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td> {{ $timeEvent->check_time }} </td>
        </tr>
        <tr>
            <td>Jenis Cuti</td>
            <td>{{ $timeEvent->timeEventType->description }}</td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>{{ $timeEvent->note }}</td>
        </tr>
        <tr>
            <td>Tahap</td>
            <td><span class="label label-default">{{ $timeEvent->stage->description }}</span></td>
        </tr>
        
        @if (!$timeEvent->is_waiting_approval) 
        <tr>
            <td>Disetujui oleh:</td>
            <td>
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $timeEvent->timeEventApprovals->first()->user->personnel_no,
                    'employee_name' => $timeEvent->timeEventApprovals->first()->user->name])
                @endcomponent 
            </td>
        </tr>
        <tr>
            <td>Disetujui pada:</td>
            <td>{{ $timeEvent->updated_at->format('d.m.Y - H:i:s') }} </td>
        </tr>
        @else 
        <tr>
            <td>Atasan:</td>
            <td>
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $timeEvent->timeEventApprovals->first()->user->personnel_no,
                    'employee_name' => $timeEvent->timeEventApprovals->first()->user->name
                ])
                @endcomponent
            </td>
        </tr>
        @endif
    </tbody>
</table>
<br />

@if ($timeEvent->is_waiting_approval)
{!! Form::model($timeEvent, ['url' => $approve_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'persetujuan?'] ) !!}
{!! Form::submit('Setuju', ['class'=>'btn btn-block btn-primary m-b-5']) !!}
{!! Form::close()!!}
{!! Form::model($timeEvent, ['url' => $reject_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'penolakan?'] ) !!}
{!! Form::submit('Tolak', ['class'=>'btn btn-block btn-danger m-b-5']) !!}
{!! Form::close()!!}
@endif
