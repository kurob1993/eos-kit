<tr style="background-color: #d4d7dd">
    <td class="text-center" style="background-color: white">IV</td>
    <td><input type="text" name="kpi_perilaku_aspek_penilaian[]" style="width: 100%" value="KPI Prilaku" readonly autocomplete="off"></td>
    <td><input type="text" name="kpi_perilaku_kode[]" style="width: 100%" value="" autocomplete="off"></td>
    <td><input type="text" name="kpi_perilaku_sasran_prestasi_kerja[]" style="width: 100%" autocomplete="off"></td>
    <td><input type="text" name="kpi_perilaku_ukuran_prestasi_kerja[]" style="width: 100%" autocomplete="off"></td>
    @if ($golongan[0] == 'A' || $golongan[0] == 'B' || $golongan[0] == 'C')
    <td><input type="text" name="kpi_perilaku_bobot[]" id="bobotPerilakuKpi"style="width: 100%; text-align: right" value="5" readonly></td>
    @else
    <td><input type="text" name="kpi_perilaku_bobot[]" id="bobotPerilakuKpi" style="width: 100%; text-align: right" value="10" readonly></td>
    @endif
    <td><input type="text" name="kpi_perilaku_keterangan[]" style="width: 100%" autocomplete="off" value="Capaian"></td>
    <td><input type="text" name="kpi_perilaku_skor[]" id="skorPerilakuKpi" style="width: 100%; text-align: right;" autocomplete="off" onkeyup="numberOnly(this.value, this.id);setNilaiKpiPerilaku();"></td>
    <td><input type="text" name="kpi_perilaku_nilai[]" id="nilaiPerilakuKpi" style="width: 100%; text-align: right;" autocomplete="off" readonly></td>
    <td></td>
</tr>