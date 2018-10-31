<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $timeEventId }}">
    <tbody>
        <tr>
            <td>Tanggal</td>
            <td> {{ $timeEvent->check_date->format('d F Y') }} </td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td> {{ $timeEvent->check_time }} </td>
        </tr>
        <tr>
            <td>Check-in/Check-out</td>
            <td>{{ $timeEvent->timeEventType->description }}</td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>{{ $timeEvent->note }}</td>
        </tr>
        <tr>
            <td>Atasan</td>
            <td>{{ $timeEvent->timeEventApprovals->first()->employee->personnelNoWithName }}</td>
        </tr>
        <tr>
            <td>Tahap</td>
            <td><span class="label label-default">{{ $timeEvent->stage->description }}</span></td>
        </tr>
    </tbody>
</table>