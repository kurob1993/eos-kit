<tr style="background-color: wheat">
    <td class="text-center" style="background-color: white">III</td>
    <td><input type="text" name="kpi_proses_aspek_penilaian[]" value="KPI Proses" style="width: 100%"  readonly></td>
    <td><input type="text" name="kpi_proses_kode[]" id="kodeKpiProses_0" style="width: 100%"></td>
    <td><input type="text" name="kpi_proses_sasran_prestasi_kerja[]" id="sasaranKpiProses_0" style="width: 100%"></td>
    <td><input type="text" name="kpi_proses_ukuran_prestasi_kerja[]" id="ukuranKpiProses_0" style="width: 100%"></td>
    <td>
        <input type="text" name="kpi_proses_bobot[]" id="bobotKpiProses_0" style="width: 100%; text-align: right"
        onkeyup=" numberOnly(this.value, this.id); CekBobotKpiProses(this.id); ">
    </td>
    <td><input type="text" style="width: 100%" id="targetKpiProses_0" value="Target" readonly></td>
    <td><input type="text" name="kpi_proses_target[]" style="width: 100%" id="skorTargetKpiProses_0"></td>
    <td></td>
    <td style="text-align: center">
        <button type="button" class="btn btn-primary btn-xs" onclick="addColumnKpiProses()"> 
            <i class="fa fa-plus-circle" aria-hidden="true"></i>
            Tambah Kolom
        </button>
    </td>
</tr>
<tr style="background-color: wheat">
    <td colspan="6"></td>
    <td><input type="text" style="width: 100%" id="realisasiKpiProses_0" value="Realisasi" readonly></td>
    <td></td>
    <td></td>
    <td style="text-align: center"></td>
</tr>
<tr id='kolom_0' style="background-color: wheat">
    <td colspan="6"></td>
    <td><input type="text" style="width: 100%" id="capaianKpiProses_0" value="Capaian" readonly></td>
    <td></td>
    <td></td>
    <td style="text-align: center"></td>
</tr>

<tr id='lastColumnKpiProses' style="display: none">
    <td colspan="5" style="text-align: right"> Total = </td>
    <td>
        <input type="text" id="totalBobotKpiProses" style="width: 100%; text-align: right">
    </td>
    <td colspan="4"></td>
</tr>