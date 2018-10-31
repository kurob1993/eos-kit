<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $leaveId }}">
    <tbody>
        <tr>
            <td>Mulai</td>
            <td> {{ $leave->start_date->format('d F Y') }} </td>
        </tr>
        <tr>
            <td>Berakhir</td>
            <td> {{ $leave->end_date->format('d F Y') }} </td>
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
            <td>{{ $leave->absenceApprovals->first()->employee->personnelNoWithName }}</td>
        </tr>
        <tr>
            <td>Tahap</td>
            <td><span class="label label-default">{{ $leave->stage->description }}</span></td>
        </tr>
    </tbody>
</table>