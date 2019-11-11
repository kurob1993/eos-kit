<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>NIK</th>
            <th>NAME</th>
            <th>TYPE</th>

            <th>Nama Organisasi</th>
            <th>Posisi</th>
            <th>Mulai</th>
            <th>Berkhir</th>
            <th>Keterangan</th>

            <th>Stage</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1 @endphp
        @foreach($activity as $value)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $value->plain_id }}</td>
            <td>{{ $value->personnel_no }}</td>
            <td>{{ $value->user['name'] }}</td>
            <td>{{ $value->type }}</td>

            <td>{{ $value->nama_organisasi }}</td>
            <td>{{ $value->posisi }}</td>
            <td>{{ $value->start_date->format('d-m-Y') }}</td>
            <td>{{ $value->end_date->format('d-m-Y') }}</td>
            <td>{{ $value->keterangan }}</td>

            <td>{{ $value->stage->description}}</td>
            
        </tr>
        @endforeach
    </tbody>
</table>