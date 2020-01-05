<table id="table-detail" class="table table-bordered table-condensed m-b-0" data-id="{{ $ski->plain_id }}">
    <tbody>
        <tr>
            <td>Nama</td>
            <td> {{ $ski->employee->PersonnelNoWithName }}</td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>{{$ski->month}}-{{$ski->year}}</td>
        </tr>
        <tr>
            <td>Atasan</td>
            <td>
                @foreach ($ski->skiApproval as $item)
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
            <td><span class="label label-default">{{ $ski->stage->description }}</span></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-condensed m-t-15" style="width: 100%">
    <thead>
        <tr>
            <th rowspan="2" class="text-center" style="vertical-align: middle; width: 5%">NO</th>
            <th rowspan="2" class="text-center" style="vertical-align: middle; width: 20%">Aspek Penilaian</th>
            <th rowspan="2" class="text-center" style="vertical-align: middle; width: 10%">Kode</th>
            <th rowspan="2" class="text-center" style="vertical-align: middle; width: 25%">Sasaran Kerja</th>
            <th rowspan="2" class="text-center" style="vertical-align: middle; width: 15%">Ukuran Prestasi Kerja</th>
            <th rowspan="2" class="text-center" style="vertical-align: middle; width: 6%">Bobot</th>
            <th colspan="3" class="text-center" style="vertical-align: middle; width: 6%">Skor</th>
            <th rowspan="2" class="text-center" style="vertical-align: middle; width: 8%">Nilai</th>
        </tr>
        <tr>
          <th>Target</th>
          <th>Realisasi</th>
          <th>Capaian</th>
        </tr>
    </thead>
    <tbody>
        @php
            $u = 0;
        @endphp
        @foreach ($ski->skiDetail as $key => $item)
            @if ($item->aspek_penilaian !== 'KPI Leadership (NSP)')
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td>{{$item->aspek_penilaian}}</td>
                <td>{{$item->kode}}</td>
                <td>{{$item->sasaran}}</td>
                <td>{{$item->ukuran}}</td>
                <td>{{$item->bobot}}</td>
                <td class="text-right">{{$item->target ? $item->target : '-'}}</td>
                <td class="text-right">{{$item->realisasi ? $item->realisasi : '-'}}</td>
                <td class="text-right">{{$item->capaian ? $item->capaian : '-'}}</td>
                <td class="text-right">{{$item->nilai}}</td>
            </tr>
            @php($u = $key+1)
            @endif
        @endforeach
            
        @foreach ($leadership as $key => $item)
            @if (isset($item['aspek_penilaian']))
            <tr>
                <td class="text-center">{{++$u}}</td>
                <td>{{$item['aspek_penilaian']}}</td>
                <td>{{$item['personnel_no']}}</td>
                <td>{{$item['name']}}</td>
                <td>{{$item['ukuran']}}</td>
                <td>{{$item['bobot']}}</td>
                <td class="text-right">{{$item['target'] ? $item['target'] : '-'}}</td>
                <td class="text-right">{{$item['realisasi'] ? $item['realisasi'] : '-'}}</td>
                <td class="text-right">{{$item['capaian'] ? $item['capaian'] : '-'}}</td>
                <td class="text-right">{{$item['nilai'] }}</td>
            </tr>
            @endif
        @endforeach

    </tbody>
</table>