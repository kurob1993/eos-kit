<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $overtime->plain_id }}">
    <tbody>
        <tr>
            <td>Mulai</td>
        <td> {{ $overtime->start_date->format('d F Y') }} {{ $overtime->from }}</td>
        </tr>
        <tr>
            <td>Berakhir</td>
            <td> {{ $overtime->end_date->format('d F Y') }} {{ $overtime->to }}</td>
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
            <td>Atasan</td>
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
            <td>Tahap</td>
            <td><span class="label label-default">{{ $overtime->stage->description }}</span></td>
        </tr>
    </tbody>
</table>