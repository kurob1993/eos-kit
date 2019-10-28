<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        table {
            border-collapse: collapse;
        }

        table,
        td,
        th {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    @php $no = 1 @endphp

    <table style="border: 1px solid black;">
        <thead>
            <tr>
                <th>ID</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Tahapan</th>
                <th>Period</th>
                <th>Jabatan</th>
                <th>Divisi</th>
                <th>Perilaku</th>
                <th>Kinerja</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ski as $value)
            @php($sum_perilaku = 0)
            @php($sum_kinerja = 0)
            <tr>
                <td>{{ $value->plain_id }}</td>
                <td>{{ $value->personnel_no }}</td>
                <td>{{ $value->user['name'] }}</td>
                <td>{{ $value->stage->description }}</td>
                <td>{{ $value->month }}/{{ $value->year }}</td>
                <td>{{ $value->employee->position_name }}</td>
                <td>{{ $value->employee->org_unit_name }}</td>
                @foreach($value->skiDetail as $klp)
                    @if ($klp->klp == 'Perilaku')
                    @php($sum_perilaku += $klp->nilai)
                    @endif
                    @if ($klp->klp == 'Kinerja')
                    @php($sum_kinerja += $klp->nilai)
                    @endif
                @endforeach
                <td>{{ $sum_perilaku }}</td>
                <td>{{ $sum_kinerja }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>