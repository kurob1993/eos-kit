<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>NIK</th>
            <th>NAME</th>
            <th>TYPE</th>
            <th>DURATION</th>
            <th>START DATE</th>
            <th>END DATE</th>
            <th>STATUS</th>
            <th>DESC</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1 @endphp
        @foreach($absence as $value)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $value->plain_id }}</td>
            <td>{{ $value->personnel_no }}</td>
            <td>{{ $value->user['name'] }}</td>
            <td>{{ $value->absenceType['text'] }}</td>
            <td>{{ $value->duration }} hari</td>
            <td>{{ $value->formatted_start_date }}</td>
            <td>{{ $value->formatted_end_date }}</td>
            <td>Error Send to SAP</td>
            <td>{{ 
                    $value->$relasi->count() > 0 ? $value->$relasi->last()->desc : ' - ' 
                }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>