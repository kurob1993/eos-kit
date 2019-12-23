<table class="table-responsive" style="width: 100%">
    <thead>
        <tr style="background-color: darkcyan; color: white">
            <th rowspan="2" class="text-center" style="width: 2%">NO</th>
            <th rowspan="2" class="text-center" style="width: 8%">Asepk Penilaian</th>
            <th rowspan="2" class="text-center" style="width: 4%">Kode</th>
            <th rowspan="2" class="text-center" style="width: 20%">Sasaran Prestasi Kerja</th>
            <th rowspan="2" class="text-center" style="width: 15%">Ukuran Prestasi Kerja</th>
            <th rowspan="2" class="text-center" style="width: 5%">Bobot</th>
            <th colspan="3" class="text-center">Keterangan</th>
            <th rowspan="2" class="text-center">Skor</th>
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
            <td><input type="text" name="ukuran_prestasi_kerja[]" style="width: 100%"></td>
            <td><input type="text" style="width: 100%; text-align: right"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
        </tr>

        <tr>
            <td colspan="2"></td>
            <td><input type="text" name="kode[]" value="3500" style="width: 100%" readonly></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" value="Enegry Consumption (Rolling)" readonly  style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" style="width: 100%"></td>
            <td><input type="text" style="width: 100%; text-align: right"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
        </tr>        

        <tr>
            <td colspan="2"></td>
            <td><input type="text" name="kode[]" value="3400" style="width: 100%" readonly></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" value="Lead Time Inventory Days (FG)" readonly  style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" style="width: 100%"></td>
            <td><input type="text" style="width: 100%; text-align: right"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
        </tr>

        <tr>
            <td colspan="2"></td>
            <td><input type="text" name="kode[]" value="3700" style="width: 100%" readonly></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" value="Lost Time Injury frequency Rate" readonly  style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" style="width: 100%"></td>
            <td><input type="text" style="width: 100%; text-align: right"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
        </tr>

        @for ($i = 0; $i < 2; $i++)
        <tr>
            <td colspan="2"></td>
            <td><input type="text" name="kode[]" value="" style="width: 100%"></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" value=""  style="width: 100%"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" style="width: 100%"></td>
            <td><input type="text" style="width: 100%; text-align: right"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
            <td><input type="text" style="width: 100%"></td>
        </tr>
        @endfor

    </tbody>
</table>