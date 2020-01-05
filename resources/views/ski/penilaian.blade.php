<table id="table-detail" class="table table-bordered table-condensed m-b-0" data-id="{{ $ski->plain_id }}">
</table>
<form method="post" action="{{route('ski.update',$ski->id)}}">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

    <table class="table-responsive" style="width: 100%">
        <thead>
            <tr>
                <th rowspan="2" class="text-center" style="vertical-align: middle; width: 3%">NO</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; width: 15%">Aspek Penilaian</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; width: 5%">Kode</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; width: 15%">Sasaran Kerja</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; width: 10%">Ukuran Prestasi Kerja</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; width: 7%">Bobot</th>
                <th colspan="3" class="text-center" style="vertical-align: middle; width: 6%">Skor</th>
                <th rowspan="2" class="text-center" style="vertical-align: middle; width: 5%">Nilai</th>
            </tr>
            <tr>
            <th class="text-center" style="vertical-align: middle; width: 6%">Target</th>
            <th class="text-center" style="vertical-align: middle; width: 6%">Realisasi</th>
            <th class="text-center" style="vertical-align: middle; width: 6%">Capaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ski->skiDetail as $key => $item)
            <tr>
                <td class="text-center">{{$key+1}}</td>
                <td> 
                    <input style="width: 100%" type="hidden" name="ski_detail_id[]" value="{{$item->id}}">
                    <input style="width: 100%" type="text" name="aspek_penilaian[]" value="{{$item->aspek_penilaian}}"> 
                </td>
                <td> <input style="width: 100%" type="text" name="kode[]" value="{{$item->kode}}"> </td>
                <td> <input style="width: 100%" type="text" name="sasaran[]" value="{{$item->sasaran}}"> </td>
                <td> <input style="width: 100%" type="text" name="ukuran[]" value="{{$item->ukuran}}"> </td>
                <td> <input style="width: 100%; text-align: right" name="bobot[]" type="text" id="bobot_{{$item->id}}" value="{{$item->bobot}}" onkeyup="numberOnly(this.value,this.id); setNilai({{$item->id}});"> </td>

                @if ($item->aspek_penilaian == 'KPI Leadership (NSP)' || $item->aspek_penilaian == 'KPI Prilaku' || $item->aspek_penilaian == 'Share Capaian')
                    <td> <input style="width: 100%; text-align: right" name="target[]" type="text" value="" readonly> </td>
                    <td> <input style="width: 100%; text-align: right" name="realisasi[]" type="text" value="" readonly> </td>
                @else
                    <td> <input style="width: 100%; text-align: right" name="target[]" type="text" id="target_{{$item->id}}" value="{{$item->target ? $item->target : '-'}}" onkeyup="numberOnly(this.value,this.id)"> </td>
                    <td> <input style="width: 100%; text-align: right" name="realisasi[]" type="text" id="realisasi_{{$item->id}}" value="{{$item->realisasi ? $item->realisasi : '-'}}" onkeyup="numberOnly(this.value,this.id)"> </td>
                @endif

                <td> <input style="width: 100%; text-align: right" name="capaian[]" type="text" id="capaian_{{$item->id}}" value="{{$item->capaian ? $item->capaian : '-'}}" onkeyup="numberOnly(this.value,this.id); setNilai({{$item->id}});"> </td>
                <td> <input style="width: 100%; text-align: right" type="text" id="nilai_{{$item->id}}" value="{{$item->nilai}}" onkeyup="numberOnly(this.value,this.id)" readonly> </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <button type="submit" class="btn btn-primary m-t-5 m-r-10 pull-right">Simpan & Approve</button>
    </div>
</form>