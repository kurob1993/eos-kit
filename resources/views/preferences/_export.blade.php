<table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>NAMA</th>
                <th>JABATAN SEKARANG</th>
                <th>DIVISI</th>
                <th>SUBDIT</th>
                <th>DIREKTORAT</th>
                <th>PERIODE</th>
                <th>PROMOSI 1</th>
                <th>PROMOSI 2</th>
                <th>PROMOSI 3</th>
                <th>MUTASI 1</th>
                <th>MUTASI 2</th>
                <th>MUTASI 3</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1 @endphp
            @foreach($preferences as $value)
            <tr>
                <td>{{ $no++ }}</td>
                <th>{{ $value[0]->sobid }}</th>
                <th>{{ $value[0]->user->name }}</th>
                <th>{{ $value[0]->user->employee->position_name }}</th>
                <th>{{ $value[0]->user->structDisp[0]->structDispSap->zhrom0007->NameofOrgUnitDivisi }}</th>
                <th>{{ $value[0]->user->structDisp[0]->structDispSap->zhrom0007->NameofOrgUnitSubDirektorat }}</th>
                <th>{{ $value[0]->user->structDisp[0]->structDispSap->zhrom0007->NameofOrgUnitDirektorat }}</th>
                <th>{{ substr($value[0]->begda, 0,4) }}</th>
                @foreach ($value as $item)
                <td>{{ $item->stext }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>