<table id="table-detail" class="table table-bordered table-condensed m-b-0" 
    data-id="{{ $permitId }}">
    <tbody>
        <tr>
            <td>Mulai</td>
            <td> {{ $permit->start_date->format('d F Y') }} </td>
        </tr>
        <tr>
            <td>Berakhir</td>
            <td> {{ $permit->end_date->format('d F Y') }} </td>
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
            <td>Keterangan</td>
            <td>{{ $permit->note }}</td>
        </tr>
        <tr>
            <td>Atasan</td>
            <td>{{ $permit->absenceApprovals->first()->user->personnelNoWithName }}</td>
        </tr>
        <tr>
            <td>Tahap</td>
            <td>
                <span class="label label-default">{{ $permit->stage->description }}
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3"><img class="center-block img-responsive" 
                src="{{ Storage::url($permit->attachment) }}" alt="">
            </td>
        </tr>
    </tbody>
</table>