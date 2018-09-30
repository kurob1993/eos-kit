<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $overtime->plain_id }}">
    <tbody>
        <tr>
            <td>Mulai</td>
            <td> {{ $overtime->formatted_start_date }} {{ $overtime->from }} </td>
        </tr>
        <tr>
            <td>Berakhir</td>
            <td> {{ $overtime->formatted_end_date }} {{ $overtime->to }} </td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td>{{ $overtime->duration }} menit</td>
        </tr>
        <tr>
            <td>Jenis Lembur</td>
            <td>{{ $overtime->overtimeReason->text }}</td>
        </tr>
        <tr>
                <td>Tahap</td>
                <td><span class="label label-default">{{ $overtime->stage->description }}</span></td>
        </tr>
        
        @if (!$overtime->is_waiting_approval) 
        <tr>
            <td>Disetujui oleh:</td>
            <td>
                @foreach ($overtime->attendanceQuotaApproval as $item)
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $item->user->personnel_no,
                    'employee_name' => $item->user->name])
                @endcomponent
                <br />
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Disetujui pada:</td>
            <td>{{ $overtime->updated_at->format('d.m.Y - H:i:s') }} </td>
        </tr>
        @else 
        <tr>
            <td>Atasan:</td>
            <td>
                @foreach ($overtime->attendanceQuotaApproval as $item)
                @if ($item->is_waiting) 
                    <i class="fa fa-clock-o"></i> 
                @elseif ($item->is_approved)
                    <i class="fa fa-check text-success"></i>
                @elseif ($item->is_rejected)
                    <i class="fa fa-times text-danger"></i> 
                @endif
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $item->user->personnel_no,
                    'employee_name' => $item->user->name
                ])
                @endcomponent
                <br />
                @endforeach
            </td>
        </tr>
        @endif 
    </tbody>
</table>
<br />
@if ($overtime->is_waiting_approval)
{!! Form::model($overtime, ['url' => $approve_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'persetujuan?'] ) !!}
{!! Form::submit('Setuju', ['class'=>'btn btn-block btn-primary m-b-5']) !!}
{!! Form::close()!!}
{!! Form::model($overtime, ['url' => $reject_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'penolakan?'] ) !!}
{!! Form::submit('Tolak', ['class'=>'btn btn-block btn-danger m-b-5']) !!}
{!! Form::close()!!}
@endif
