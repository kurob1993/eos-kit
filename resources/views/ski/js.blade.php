function keyPress() {
    $(document).keydown(function (e) {
        e = e || window.event;
        var keyCode = e.keyCode || e.which;
        if (keyCode == '13' || e.which == 40) { //arrow key
            var id = e.target.id;
            id = id.split("_");

            id = id[0] + '_' + (Number(id[1]) + 1);

            $('#' + id).focus();

            e.preventDefault();
            return false;
        }

        if (e.which == 38) { //arrow key
            var id = e.target.id;
            id = id.split("_");

            id = id[0] + '_' + (Number(id[1]) - 1);

            $('#' + id).focus();

            e.preventDefault();
            return false;
        }

    });

}

function setAutoComplete() {
    $('input').attr('autocomplete', 'off');
}

function numberOnly(value, id) {
    value = value.replace(/[^0-9\.]/g, '');
    $('#' + id).val(value);
    $('#' + id).css('text-align','right');
}

// kpi share
function setNilaiKpiShare(id) {
    var bobot = Number($('#bobotShareKpi').val());
    var skor = Number($('#skorShareKpi').val());

    $('#nilaiShareKpi').val( (skor*bobot)/10 );
}

// kpi hasil
function setNilaiKpiHasil() {
    var count = Number($('#last_id_kpi_hasil').val());
    var sum = 0;
    for (let index = 0; index <= count; index++) {
        var capaian = Number($('#skorCapaianKpiHasil_' + index).val());
        var bobot = Number($('#bobotKpiHasil_' + index).val());        
        var nilai = (bobot * capaian) / 10;
        $('#nilaiKpiHasil_' + index).val(nilai);
        sum += nilai;
    }

    $('#totalNilaiKpiHasil').val(sum);
}

function CekBobotKpiHasil(id) {
    var count = Number($('#last_id_kpi_hasil').val());
    var sum = 0;
    for (let index = 0; index <= count; index++) {
        var bobot = Number($('#bobotKpiHasil_' + index).val());
        sum += bobot;
    }
    if (sum > 65) {
        alert('Bobot tidak boleh lebih dari 65%');
        bobot = Number($('#' + id).val());
        sum = sum - bobot;
        $('#' + id).val('');
        // console.log(id);
    }

    if (sum < 65) {
        $('#totalBobotKpiHasil').css("background-color", "PINK");
    } else {
        $('#totalBobotKpiHasil').css("background-color", "#FFF");
    }

    $('#totalBobotKpiHasil').val(sum);
}

function addColumnKpiHasil(){
    var index = Number( $('#last_id_kpi_hasil').val() );
    $('#last_id_kpi_hasil').val(index+1);

    var kolom = '<tr style="background-color: #d4d7dd">'+
        '<td colspan="2"><input type="hidden" name="kpi_hasil_aspek_penilaian[]" value="KPI Hasil"></td>'+
        '<td><input type="text" name="kpi_hasil_kode[]" id="kodeKpiHasil_'+(index+1)+'" style="width: 100%"></td>'+
        '<td><input type="text" name="kpi_hasil_sasran_prestasi_kerja[]" id="sasaranKpiHasil_'+(index+1)+'" style="width: 100%"></td>'+
        '<td><input type="text" name="kpi_hasil_ukuran_prestasi_kerja[]" id="ukuranKpiHasil_'+(index+1)+'" style="width: 100%"></td>'+
        '<td>'+
            '<input type="text" name="kpi_hasil_bobot[]" id="bobotKpiHasil_'+(index+1)+'" style="width: 100%; text-align: right" '+
            'onkeyup="numberOnly(this.value, this.id); CekBobotKpiHasil(this.id); setNilaiKpiHasil()" autocomplete="off">'+
        '</td>'+
        '<td><input type="text" style="width: 100%" id="targetKpiHasil_'+(index+1)+'" value="Target" readonly></td>'+
        '<td><input type="text" name="kpi_hasil_target[]" style="width: 100%; text-align: right" id="skorTargetKpiHasil_'+(index+1)+'" onkeyup="numberOnly(this.value, this.id);"></td>'+
        '<td></td>'+
        '<td style="text-align: center"></td>'+
    '</tr>'+
    '<tr style="background-color: #d4d7dd">'+
        '<td colspan="6"></td>'+
        '<td><input type="text" style="width: 100%" id="realisasiKpiHasil_'+(index+1)+'" value="Realisasi" readonly></td>'+
        '<td><input type="text" name="kpi_hasil_realisasi[]" style="width: 100%; text-align: right; " id="skorRealisasiKpiHasil_'+(index+1)+'" onkeyup="numberOnly(this.value, this.id);"></td>'+
        '<td></td>'+
        '<td style="text-align: center"></td>'+
    '</tr>'+
    '<tr id="kolom_'+(index+1)+'" style="background-color: #d4d7dd">'+
        '<td colspan="6"></td>'+
        '<td><input type="text" style="width: 100%" id="capaianKpiHasil_'+(index+1)+'" value="Capaian" readonly></td>'+
        '<td><input type="text" name="kpi_hasil_capaian[]" style="width: 100%; text-align: right; " id="skorCapaianKpiHasil_'+(index+1)+'" onkeyup="setNilaiKpiHasil(this.id); numberOnly(this.value, this.id);"></td>'+
        '<td><input type="text" name="kpi_hasil_nilai[]" style="width: 100%; text-align: right; " id="nilaiKpiHasil_'+(index+1)+'" readonly></td>'+
        '<td style="text-align: center"></td>'+
    '</tr>';

    $('#lastColumnKpiHasil').before(kolom);
}

//KPI PROSES
function CekBobotKpiProses(id) {
    var count = Number($('#last_id_kpi_proses').val());
    var sum = 0;
    for (let index = 0; index <= count; index++) {
        var bobot = Number($('#bobotKpiProses_' + index).val());
        sum += bobot;
    }
    if (sum > 10) {
        alert('Bobot tidak boleh lebih dari 10%');
        bobot = Number($('#' + id).val());
        sum = sum - bobot;
        $('#' + id).val('');
    }

    if (sum < 10) {
        $('#totalBobotKpiProses').css("background-color", "PINK");
    } else {
        $('#totalBobotKpiProses').css("background-color", "#FFF");
    }

    $('#totalBobotKpiProses').val(sum);
}

function setNilaiKpiProses() {    
    var count = Number($('#last_id_kpi_proses').val());
    var sum = 0;
    for (let index = 0; index <= count; index++) {
        var capaian = Number($('#skorCapaianKpiProses_' + index).val());
        var bobot = Number($('#bobotKpiProses_' + index).val());
        var nilai = (bobot * capaian) / 10;
        $('#nilaiKpiProses_' + index).val(nilai)
        sum += nilai;
    }

    $('#totalNilaiKpiProses').val(sum);
}

function addColumnKpiProses(){
    var index = Number( $('#last_id_kpi_proses').val() );
    $('#last_id_kpi_proses').val(index+1);

    var kolom = '<tr style="background-color: #d4d7dd">'+
        '<td colspan="2"><input type="hidden" name="kpi_proses_aspek_penilaian[]" value="KPI Proses"></td>'+
        '<td><input type="text" name="kpi_proses_kode[]" id="kodeKpiProses_'+(index+1)+'" style="width: 100%"></td>'+
        '<td><input type="text" name="kpi_proses_sasran_prestasi_kerja[]" id="sasaranKpiProses_'+(index+1)+'" style="width: 100%"></td>'+
        '<td><input type="text" name="kpi_proses_ukuran_prestasi_kerja[]" id="ukuranKpiProses_'+(index+1)+'" style="width: 100%"></td>'+
        '<td>'+
            '<input type="text" name="kpi_proses_bobot[]" id="bobotKpiProses_'+(index+1)+'" style="width: 100%; text-align: right" '+
            'onkeyup=" numberOnly(this.value, this.id); CekBobotKpiProses(this.id); setNilaiKpiProses();">'+
        '</td>'+
        '<td><input type="text" style="width: 100%" id="targetKpiProses_'+(index+1)+'" value="Target" readonly></td>'+
        '<td><input type="text" name="kpi_proses_target[]" style="width: 100%; text-align: right" id="skorTargetKpiProses_'+(index+1)+'" onkeyup="numberOnly(this.value, this.id);"></td>'+
        '<td></td>'+
        '<td style="text-align: center"></td>'+
    '</tr>'+
    '<tr style="background-color: #d4d7dd">'+
        '<td colspan="6"></td>'+
        '<td><input type="text" style="width: 100%" id="realisasiKpiProses_'+(index+1)+'" value="Realisasi" readonly></td>'+
        '<td><input type="text" name="kpi_proses_realisasi[]" style="width: 100%; text-align: right; " id="skorRealisasiKpiProses_'+(index+1)+'" onkeyup="numberOnly(this.value, this.id);"></td>'+
        '<td></td>'+
        '<td style="text-align: center"></td>'+
    '</tr>'+
    '<tr id="kolom_'+(index+1)+'" style="background-color: #d4d7dd">'+
        '<td colspan="6"></td>'+
        '<td><input type="text" style="width: 100%" id="capaianKpiProses_'+(index+1)+'" value="Capaian" readonly></td>'+
        '<td><input type="text" name="kpi_proses_capaian[]" style="width: 100%; text-align: right; " id="skorCapaianKpiProses_'+(index+1)+'" onkeyup="numberOnly(this.value, this.id); setNilaiKpiProses();" ></td>'+
        '<td><input type="text" name="kpi_proses_nilai[]" style="width: 100%; text-align: right; " id="nilaiKpiProses_'+(index+1)+'" readonly></td>'+
        '<td style="text-align: center"></td>'+
    '</tr>';

    $('#lastColumnKpiProses').before(kolom);
}

// kpi perilkau
function setNilaiKpiPerilaku() {
    var bobot = Number( $('#bobotPerilakuKpi').val() );
    var skor = Number( $('#skorPerilakuKpi').val() );
    $('#nilaiPerilakuKpi').val((bobot*skor)/10);
}

// kpi leadership
function setNilaiKpiLeadership() {
    var count = '{{count($setingkat)}}';
    for (let index = 0; index <= count; index++) {
        var bobot = Number( $('#bobotLeadershipKpi_'+index).val() );
        var skor = Number( $('#skorLeadershipKpi_'+index).val() );
        $('#nilaiLeadershipKpi_'+index).val((bobot*skor)/10);
    }
}
