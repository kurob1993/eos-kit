@foreach ($setingkat as $key => $item)
<tr style="background-color: #d4d7dd">
    @if ($key == 0)
    <td class="text-center" style="background-color: white">V</td>
    <td><input type="text" name="kpi_leadership_aspek_penilaian[]" style="width: 100%" value="KPI Leadership (NSP)" readonly autocomplete="off"></td>
    @else
    <td colspan="2"></td>
    @endif
    <td><input type="text" name="kpi_leadership_kode[]" style="width: 100%" value="{{$item->empnik}}" readonly autocomplete="off"></td>
    <td><input type="text" name="kpi_leadership_sasran_prestasi_kerja[]" value="{{$item->empname}}" readonly style="width: 100%" autocomplete="off"></td>
    <td><input type="text" name="kpi_leadership_ukuran_prestasi_kerja[]" style="width: 100%" autocomplete="off"></td>
    <td><input type="text" name="kpi_leadership_bobot[]" id="bobotLeadershipKpi_{{$key}}" style="width: 100%; text-align: right" value="{{ round( 5/count($setingkat) , 2) }}"></td>
    <td><input type="text" name="kpi_leadership_keterangan[]" style="width: 100%" autocomplete="off" value="Capaian"></td>
    <td><input type="text" name="kpi_leadership_skor[]" id="skorLeadershipKpi_{{$key}}" style="width: 100%; text-align: right;" autocomplete="off" onkeyup="numberOnly(this.value, this.id);setNilaiKpiLeadership();"></td>
    <td><input type="text" name="kpi_leadership_nilai[]" id="nilaiLeadershipKpi_{{$key}}" style="width: 100%; text-align: right;" autocomplete="off" readonly></td>
    <td></td>
</tr>
@endforeach

