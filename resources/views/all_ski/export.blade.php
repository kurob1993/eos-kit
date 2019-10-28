<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        table {
            border-collapse: collapse;
        }
        
        table, td, th {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    @php $no = 1 @endphp
    @foreach($ski as $value)
    <table style="border: 1px solid black;">
        <tbody>
            <tr>
                <td>Tahapan</td>
                <td>{{ $value->stage->description }}</td>
            </tr>
            <tr>
                <td>Period</td>
                <td>{{ $value->month }}/{{ $value->year }}</td>
            </tr>
            <tr>
                <td>NIK/Nama</td>
                <td>{{ $value->personnel_no }}/{{ $value->user['name'] }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>{{ $value->employee->position_name }}</td>
            </tr>
            <tr>
                <td>Divisi</td>
                <td>{{ $value->employee->org_unit_name }}</td>
            </tr>
            <tr>
                <td>Perlu Dipertahankan</td>
                <td></td>
            </tr>
            <tr>
                <td>Perlu Diperbaiki</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <th>KLP</th>
                <th>SASARAN KERJA</th>
                <th>KODE</th>
                <th>UKURAN PRESTASI KERJA</th>
                <th>BOBOT</th>
                <th>SKOR</th>
                <th>NILAI</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($value->skiDetail as $item)
            <tr>
                <td>{{$item->klp}}</td>
                <td>{{$item->sasaran}}</td>
                <td>{{$item->kode}}</td>
                <td>{{$item->ukuran}}</td>
                <td>{{$item->bobot}}</td>
                <td>{{$item->skor}}</td>
                <td>{{$item->nilai}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <hr>
    @endforeach
</body>

</html>