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
            <td>Perilaku</td>
            <td>{{$ski->perilaku}}</td>
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
            <th class="text-center" style="width: 5%">NO</th>
            <th class="text-center" style="width: 10%">KLP</th>
            <th class="text-center" style="width: 30%">Sasaran Kerja</th>
            <th class="text-center" style="width: 10%">Kode</th>
            <th class="text-center" style="width: 25%">Ukuran Prestasi Kerja</th>
            <th class="text-center" style="width: 6%">Bobot</th>
            <th class="text-center" style="width: 6%">Skor</th>
            <th class="text-center" style="width: 8%">Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ski->skiDetail as $key => $item)
        <tr>
            <td class="text-center">{{$key+1}}</td>
            <td>{{$item->klp}}</td>
            <td>{{$item->sasaran}}</td>
            <td>{{$item->kode}}</td>
            <td>{{$item->ukuran}}</td>
            <td>{{$item->bobot}}</td>
            <td>{{$item->skor}}</td>
            <td>{{$item->nili}}</td>
        </tr>
        @endforeach
    </tbody>
</table>