<table class="table-responsive" style="width: 100%">
    <thead>
        <tr style="background-color: darkcyan; color: white">
            <th rowspan="2" class="text-center" style="width: 2%">NO</th>
            <th rowspan="2" class="text-center" style="width: 8%">Asepk Penilaian</th>
            <th rowspan="2" class="text-center" style="width: 4%">Kode</th>
            <th rowspan="2" class="text-center" style="width: 20%">Sasaran Prestasi Kerja</th>
            <th rowspan="2" class="text-center" style="width: 17%">Ukuran Prestasi Kerja</th>
            <th rowspan="2" class="text-center" style="width: 7%">Bobot(%)</th>
            <th colspan="3" class="text-center">Keterangan</th>
            <th rowspan="2" class="text-center" style="width: 8%">Nilai</th>
        </tr>
        <tr style="background-color: darkcyan; color: white">
            <th class="text-center">Target</th>
            <th class="text-center">Realisasi</th>
            <th class="text-center">Capaian</th>
        </tr>
    </thead>
    <tbody id="tbodyPerilaku">

        <tr>
            <td class="text-center">II</td>
            <td><input type="text" name="aspek_penilaian[]" value="KPI Hasil" style="width: 100%"  readonly></td>
            <td><input type="text" name="kode[]" value="3100" style="width: 100%" readonly></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" value="OEE (Rolling) Yield(Rolling)" readonly  style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" id="ukuranPrestasiKerja_0" style="width: 100%"></td>
            <td>
                <input type="text" name="bobot[]" id="bobotKpiHasil_0" style="width: 100%; text-align: right" required
                onkeyup="
                    numberOnly(this.value, this.id);
                    CekBobotKpiHasil(this.id);
                    setNilaiKpiHasil(this.id);
                ">
            </td>
            <td><input type="text" style="width: 100%" id="targetKpiHasil_0" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="realisasiKpiHasil_0" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="capaianKpiHasil_0" onkeyup="
                numberOnly(this.value, this.id);
                setNilaiKpiHasil(this.id);
            "></td>
            <td><input type="text" style="width: 100%" id="nilaiKpiHasil_0" readonly></td>
        </tr>

        <tr>
            <td colspan="2"> <input type="hidden" name="aspek_penilaian[]" value="KPI Hasil" style="width: 100%"> </td>
            <td><input type="text" name="kode[]" value="3500" style="width: 100%" readonly></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" value="Enegry Consumption (Rolling)" readonly  style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" id="ukuranPrestasiKerja_1" style="width: 100%"></td>
            <td>
                <input type="text" name="bobot[]" id="bobotKpiHasil_1" style="width: 100%; text-align: right" required
                onkeyup="
                    numberOnly(this.value, this.id);
                    CekBobotKpiHasil(this.id);
                    setNilaiKpiHasil(this.id);
                ">
            </td>
            <td><input type="text" style="width: 100%" id="targetKpiHasil_1" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="realisasiKpiHasil_1" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="capaianKpiHasil_1" onkeyup="
                numberOnly(this.value, this.id);
                setNilaiKpiHasil(this.id);
            "></td>
            <td><input type="text" style="width: 100%" id="nilaiKpiHasil_1" readonly></td>
        </tr>        

        <tr>
            <td colspan="2"> <input type="hidden" name="aspek_penilaian[]" value="KPI Hasil" style="width: 100%"> </td>
            <td><input type="text" name="kode[]" value="3400" style="width: 100%" readonly></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" value="Lead Time Inventory Days (FG)" readonly  style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" id="ukuranPrestasiKerja_2" style="width: 100%"></td>
            <td>
                <input type="text" name="bobot[]" id="bobotKpiHasil_2" style="width: 100%; text-align: right" required
                onkeyup="
                    numberOnly(this.value, this.id);
                    CekBobotKpiHasil(this.id);
                    setNilaiKpiHasil(this.id);
                ">
            </td>
            <td><input type="text" style="width: 100%" id="targetKpiHasil_2" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="realisasiKpiHasil_2" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="capaianKpiHasil_2" onkeyup="
                numberOnly(this.value, this.id);
                setNilaiKpiHasil(this.id);"
            ></td>
            <td><input type="text" style="width: 100%" id="nilaiKpiHasil_2" readonly></td>
        </tr>

        <tr>
            <td colspan="2"> <input type="hidden" name="aspek_penilaian[]" value="KPI Hasil" style="width: 100%"> </td>
            <td><input type="text" name="kode[]" value="3700" style="width: 100%" readonly></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" value="Lost Time Injury frequency Rate" readonly  style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" id="ukuranPrestasiKerja_3" style="width: 100%"></td>
            <td>
                <input type="text" name="bobot[]" id="bobotKpiHasil_3" style="width: 100%; text-align: right" required
                onkeyup="
                    numberOnly(this.value, this.id);
                    CekBobotKpiHasil(this.id);
                    setNilaiKpiHasil(this.id);
                ">
            </td>
            <td><input type="text" style="width: 100%" id="targetKpiHasil_3" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="realisasiKpiHasil_3" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="capaianKpiHasil_3" onkeyup="
                numberOnly(this.value, this.id);
                setNilaiKpiHasil(this.id);
            "></td>
            <td><input type="text" style="width: 100%" id="nilaiKpiHasil_3" readonly></td>
        </tr>

        @php($u = 0)
        @for ($i = 4; $i < 6; $i++)
        @php($u = $i)
        <tr>
            <td colspan="2"> <input type="hidden" name="aspek_penilaian[]" value="KPI Hasil" style="width: 100%"> </td>
            <td><input type="text" name="kode[]" value="" style="width: 100%"></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" value=""  style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" id="ukuranPrestasiKerja_{{$i}}" style="width: 100%"></td>
            <td>
                <input type="text" name="bobot[]" id="bobotKpiHasil_{{$i}}" style="width: 100%; text-align: right" required
                onkeyup="
                    numberOnly(this.value, this.id);
                    CekBobotKpiHasil(this.id);
                    setNilaiKpiHasil(this.id);
                ">
            </td>
            <td><input type="text" style="width: 100%" id="targetKpiHasil_{{$i}}" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="realisasiKpiHasil_{{$i}}" onkeyup="numberOnly(this.value, this.id);"></td>
            <td><input type="text" style="width: 100%" id="capaianKpiHasil_{{$i}}" onkeyup="
                numberOnly(this.value, this.id);
                setNilaiKpiHasil(this.id);
            "></td>
            <td><input type="text" style="width: 100%" id="nilaiKpiHasil_{{$i}}" readonly></td>
        </tr>
        @endfor

        <tr>
            <td colspan="5" style="text-align: right"> Total = </td>
            <td>
                <input type="text" id="totalBobotKpiHasil" style="width: 100%; text-align: right">
            </td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td>
                <input type="text" id="totalNilaiKpiHasil" style="width: 100%; text-align: right">
            </td>
        </tr>

    </tbody>
</table>
<input type="text" id="last_id_kpi_hasil" value="{{$u}}" style="width: 100%">