<table class="table-responsive" style="width: 100%">
    <thead>
        <tr style="background-color: darkcyan; color: white">
            <th class="text-center" style="width: 2%">NO</th>
            <th class="text-center" style="width: 8%">Asepk Penilaian</th>
            <th class="text-center" style="width: 4%">Kode</th>
            <th class="text-center" style="width: 20%">Sasaran Prestasi Kerja</th>
            <th class="text-center" style="width: 10%">Ukuran Prestasi Kerja</th>
            <th class="text-center" style="width: 6%">Bobot</th>
            <th class="text-center" style="width: 6%">Keterangan</th>
            <th class="text-center" style="width: 6%">Skor</th>
            <th class="text-center" style="width: 8%">Nilai</th>
        </tr>
    </thead>
    <tbody id="tbodyPerilaku">
        <tr>
        <td class="text-center">I</td>
            <td><input type="text" name="aspek_penilaian[]" style="width: 100%" value="Share KPI" readonly autocomplete="off"></td>
            <td><input type="text" name="kode[]" style="width: 100%" value="" autocomplete="off"></td>
            <td><input type="text" name="sasran_prestasi_kerja[]" style="width: 100%" autocomplete="off"></td>
            <td><input type="text" name="ukuran_prestasi_kerja[]" style="width: 100%" autocomplete="off"></td>
            <td>
                <input type="text" name="bobot[]" id="bobotShareKpi" value="15" style="width: 100%; text-align: right" readonly
                onkeyup="setNilaiPerilaku();">
            </td>
            <td><input type="text" name="keterangan[]" style="width: 100%" autocomplete="off" value="Capaian"></td>
            <td>
                <input type="text" name="skor[]" id="skorShareKpi" style="width: 100%; text-align: right" required
                onkeyup="
                    setNilaiPerilaku();
                    checkSkorPerilaku(this.value, '');
                    numberOnly(this.value,this.id);
                    setNilaiShareKpi();
                ">
            </td>
            <td><input type="text" name="nilai[]" id="nilaiShareKpi" style="width: 100%; text-align: right" readonly ></td>
        </tr>
    </tbody>
</table>