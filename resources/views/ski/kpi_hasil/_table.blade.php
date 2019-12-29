<tr style="background-color: wheat">
    <td class="text-center" style="background-color: white">II</td>
    <td><input type="text" name="kpi_hasil_aspek_penilaian[]" value="KPI Hasil" style="width: 100%"  readonly></td>
    <td><input type="text" name="kpi_hasil_kode[]" id="kodeKpiHasil_0" style="width: 100%"></td>
    <td><input type="text" name="kpi_hasil_sasran_prestasi_kerja[]" id="sasaranKpiHasil_0" style="width: 100%"></td>
    <td><input type="text" name="kpi_hasil_ukuran_prestasi_kerja[]" id="ukuranKpiHasil_0" style="width: 100%"></td>
    <td>
        <input type="text" name="kpi_hasil_bobot[]" id="bobotKpiHasil_0" style="width: 100%; text-align: right"
        onkeyup=" numberOnly(this.value, this.id); CekBobotKpiHasil(this.id); ">
    </td>
    <td><input type="text" style="width: 100%" id="targetKpiHasil_0" value="Target" readonly></td>
    <td><input type="text" name="kpi_hasil_target[]" style="width: 100%" id="skorTargetKpiHasil_0"></td>
    <td></td>
    <td style="text-align: center">
        <button type="button" class="btn btn-primary btn-xs" onclick="addColumnKpiHasil()"> 
            <i class="fa fa-plus-circle" aria-hidden="true"></i>
            Tambah Kolom
        </button>
    </td>
</tr>
<tr style="background-color: wheat">
    <td colspan="6"></td>
    <td><input type="text" style="width: 100%" id="realisasiKpiHasil_0" value="Realisasi" readonly></td>
    <td></td>
    <td></td>
    <td style="text-align: center"></td>
</tr>
<tr id='kolom_0' style="background-color: wheat">
    <td colspan="6"></td>
    <td><input type="text" style="width: 100%" id="capaianKpiHasil_0" value="Capaian" readonly></td>
    <td></td>
    <td></td>
    <td style="text-align: center"></td>
</tr>

<tr id='lastColumnKpiHasil' style="display: none">
    <td colspan="5" style="text-align: right"> Total = </td>
    <td>
        <input type="text" id="totalBobotKpiHasil" style="width: 100%; text-align: right">
    </td>
    <td colspan="4"></td>
</tr>