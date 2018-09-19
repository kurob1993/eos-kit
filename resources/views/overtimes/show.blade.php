<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $overtimeId }}">
    <tbody>
        <tr>
            <td>Mulai</td>
            <td> {{ $overtime->start_date->format('d F Y') }} </td>
        </tr>
        <tr>
            <td>Berakhir</td>
            <td> {{ $overtime->end_date->format('d F Y') }} </td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td>{{ $overtime->duration }} hari</td>
        </tr>
        <tr>
            <td>Jenis Lembur</td>
            <td>{{ $overtime->overtimeReason->text }}</td>
        </tr>
        <tr>
            <td>Atasan</td>
            <td>{{ $overtime->attendanceQuotaApproval->first()->user->personnelNoWithName }}</td>
        </tr>
        <tr>
            <td>Tahap</td>
            <td><span class="label label-default">{{ $overtime->stage->description }}</span></td>
        </tr>
    </tbody>
</table>