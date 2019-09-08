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
                <th>{{ $value[0]->preferdisPeriode->start_date }} s/d {{ $value[0]->preferdisPeriode->finish_date }}</th>
                @php $no1 = 1 @endphp
                @foreach ($value as $item)
                @php $no1 = 1 @endphp
                    @if( $item->zhrom0007->LvlOrg != substr($value[0]->user->structDisp[0]->emppersk,0,1))
                    <td>{{ $no1++ }} {{ $item->stext }}</td>
                    @endif
                @endforeach
                @if($no1 < 3)
                @for ($no2=1;  $no2 <= 3-$no1; $no2++)
                    <td>Null</td>
                @endfor
                @endif
                @foreach ($value as $item)
                    @if( $item->zhrom0007->LvlOrg == substr($value[0]->user->structDisp[0]->emppersk,0,1))
                    <td>{{ $item->stext }}</td>
                    @endif
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>