<table class="table-responsive" style="width: 100%">
    <thead>
        <tr style="background-color: darkcyan; color: white">
            <th rowspan="2" class="text-center" style="width: 2%">NO</th>
            <th rowspan="2" class="text-center">Asepk Penilaian</th>
            <th rowspan="2" class="text-center" style="width: 4%">Kode</th>
            <th rowspan="2" class="text-center">Sasaran Prestasi Kerja</th>
            <th rowspan="2" class="text-center">Ukuran Prestasi Kerja</th>
            <th rowspan="2" class="text-center" style="width: 10%">Bobot(%)</th>
            <th class="text-center" style="width: 10%">Target</th>
            <th class="text-center" style="width: 10%">Aksi</th>
        </tr>
    </thead>
    <tbody id="tbodyPerilaku">
        <tr id='kolom_0'>
            <td class="text-center">II</td>
            <td><input type="text" name="aspek_penilaian[]" value="KPI Hasil" style="width: 100%"  readonly></td>
            <td><input type="text" name="kode[]" id="kodeKpiHasil_0" style="width: 100%"></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" id="sasaranKpiHasil_0" style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" id="ukuranKpiHasil_0" style="width: 100%"></td>
            <td>
                <input type="text" name="bobot[]" id="bobotKpiHasil_0" style="width: 100%; text-align: right" required
                onkeyup="
                    numberOnly(this.value, this.id);
                    CekBobotKpiHasil(this.id);
                    setNilaiKpiHasil(this.id);
                ">
            </td>
            <td><input type="text" style="width: 100%" id="targetKpiHasil_0" onkeyup="numberOnly(this.value, this.id);"></td>
            <td style="text-align: center">
                <button type="button" class="btn btn-primary btn-xs" onclick="addColumnKpiHasil()"> 
                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                    Tambah Kolom
                </button>
            </td>
        </tr>

        <tr id='lastColumnKpiHasil'>
            <td colspan="5" style="text-align: right"> Total = </td>
            <td>
                <input type="text" id="totalBobotKpiHasil" style="width: 100%; text-align: right">
            </td>
            <td></td>
            <td></td>
        </tr>

    </tbody>
</table>
<input type="hidden" id="last_id_kpi_hasil" value="0" style="width: 100%">