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
            <td>Tujuan</td>
            <td>{{ $travel->kota }}</td>
        </tr> 
        <tr>
            <td>Durasi</td>
            <td>{{ $travel->duration }} hari</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>
                @if($travel->lampiran != null)
                    <i class="fa fa-check-circle"></i>
                @else
                    <i class="fa fa-times-circle"></i>
                @endif
            </td>
        </tr>
        <tr>
            <td>Atasan</td>
            <td>
                @foreach ($travel->travelApproval as $item)
                @component('layouts._personnel-no-with-name', [
                    'personnel_no' => $item->employee->personnel_no,
                    'employee_name' => $item->employee->name])
                @endcomponent
                <!-- $travel->travelApproval->status_id -->
                <br />
                @endforeach
            </td>
        </tr>
        <tr>
        </tr>
    </tbody>
</table>