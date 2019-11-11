<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>NIK</th>
            <th>NAME</th>
            <th>Jenis Kegiatan</th>
            <th>ID Posisi</th>
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
            <td>{{ $value->jenis_kegiatan }}</td>
            <td>{{ $value->position->id }}</td>
            <td>{{ $value->position->name }}</td>
            <td>{{ $value->start_date->format('d-m-Y') }}</td>
            <td>{{ $value->end_date->format('d-m-Y') }}</td>
            <td>{{ $value->keterangan }}</td>

            <td>{{ $value->stage->description}}</td>
            
        </tr>
        @endforeach
    </tbody>
</table>