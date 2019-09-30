<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $travel->plain_id }}">
    <tbody>
        <tr>
            <td>Nama</td>
        <td> {{ $travel->employee->PersonnelNoWithName }}</td>
        </tr>
        <tr>
            <td>Mulai</td>
        <td> {{ $travel->start_date }} </td>
        </tr>
        <tr>
            <td>Berakhir</td>
            <td> {{ $travel->end_date }} </td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td>{{ $travel->duration }} hari</td>
        </tr>
        {{-- <tr>
            <td>Jenis Lembur</td>
            <td>{{ $travel->overtimeReason->text }}</td>
        </tr> --}}
        <tr>
            <td>Atasan</td>
            <td>
                @foreach ($travel->travelApproval as $item)
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $item->employee->personnel_no,
                    'employee_name' => $item->employee->name])
                @endcomponent
                <br />
                @endforeach
            </td>
        </tr>
        <tr>
            <td>Tahap</td>
            <td><span class="label label-default">{{ $travel->stage->description }}</span></td>
        </tr>
    </tbody>
</table>