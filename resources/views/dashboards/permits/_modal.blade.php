<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $permitId }}">
    <tbody>
        <tr>
            <td>Mulai</td>
            <td> {{ $permit->formatted_start_date }} </td>
        </tr>
        <tr>
            <td>Berakhir</td>
            <td> {{ $permit->formatted_end_date }} </td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td>{{ $permit->duration }} hari</td>
        </tr>
        <tr>
            <td>Jenis Izin</td>
            <td>{{ $permit->permitType->text }}</td>
        </tr>
        <tr>
            <td colspan="3"><img class="center-block img-responsive" 
                src="{{ Storage::url($permit->attachment) }}" alt="">
            </td>
        </tr>
        <tr>
            <td>Tahap</td>
            <td><span class="label label-default">{{ $permit->stage->description }}</span></td>
        </tr>
        @if ($permit->is_sent_to_sap) 
        <tr>
            <td>Disetujui oleh:</td>
            <td>
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $permit->absenceApprovals->first()->user->personnel_no,
                    'employee_name' => $permit->absenceApprovals->first()->user->name])
                @endcomponent 
            </td>
        </tr>
        <tr>
            <td>Disetujui pada:</td>
            <td>{{ $permit->updated_at->format('d.m.Y - H:i:s') }} </td>
        </tr>
        @else 
        <tr>
            <td>Atasan:</td>
            <td>
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $permit->permitApprovals->first()->user->personnel_no,
                    'employee_name' => $permit->permitApprovals->first()->user->name
                ])
                @endcomponent
            </td>
        </tr>
        @endif        
    </tbody>
</table>
<br />
@if (!$permit->stage->is_sent_to_sap)
{!! Form::model($permit, ['url' => $approve_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'persetujuan?'] ) !!}
{!! Form::submit('Setuju', ['class'=>'btn btn-block btn-primary m-b-5']) !!}
{!! Form::close()!!}
{!! Form::model($permit, ['url' => $reject_url, 'method' => 'post', 
    'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message . 'penolakan?'] ) !!}
{!! Form::submit('Tolak', ['class'=>'btn btn-block btn-danger m-b-5']) !!}
{!! Form::close()!!}
@endif
