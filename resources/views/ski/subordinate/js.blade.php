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
}

// kpi hasil
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

    var kolom = '<tr id="kolom_'+(index+1)+'">'+
        '<td colspan="2"></td>'+
        '<td><input type="text" name="kode[]" id="kodeKpiHasil_'+ (index+1) +'" value="{{1}}" style="width: 100%"></td>'+
        '<td><input type="text" name="sasran_prestasi_kerja[]" id="sasaranKpiHasil_'+ (index+1) +'" style="width: 100%"></td>'+
        '<td><input type="text" name="ukuran_prestasi_kerja[]" id="ukuranKpiHasil_'+ (index+1) +'" style="width: 100%"></td>'+
        '<td>'+
            '<input type="text" name="bobot[]" id="bobotKpiHasil_'+ (index+1) +'" style="width: 100%; text-align: right" required onkeyup="'+
                'numberOnly(this.value, this.id);'+
                'CekBobotKpiHasil(this.id);'+
                'setNilaiKpiHasil(this.id);'+
            '">'+
        '</td>'+
        '<td><input type="text" style="width: 100%" id="targetKpiHasil_'+ (index+1) +'" onkeyup="numberOnly(this.value, this.id);"></td>'+
        '<td style="text-align: center">'+
        '</td>'+
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

function setNilaiKpiProses(id) {
    var count = Number($('#last_id_kpi_proses').val());
    var sum = 0;
    for (let index = 0; index <= count; index++) {
        var capaian = Number($('#capaianKpiProses_' + index).val());
        var bobot = Number($('#bobotKpiProses_' + index).val());
        var nilai = (bobot * capaian) / 10;
        $('#nilaiKpiHasil_' + index).val(nilai)
        sum += nilai;
    }

    $('#totalNilaiKpiHasil').val(sum);
}

function addColumnKpiProses(){
    var index = Number( $('#last_id_kpi_proses').val() );
    $('#last_id_kpi_proses').val(index+1);

    var kolom = '<tr id="kolom_'+(index+1)+'">'+
        '<td colspan="2"></td>'+
        '<td><input type="text" name="kode[]" id="kodeKpiProses_'+ (index+1) +'" value="" style="width: 100%"></td>'+
        '<td><input type="text" name="sasran_prestasi_kerja[]" id="sasaranKpiProses_'+ (index+1) +'" style="width: 100%"></td>'+
        '<td><input type="text" name="ukuran_prestasi_kerja[]" id="ukuranKpiProses_'+ (index+1) +'" style="width: 100%"></td>'+
        '<td>'+
            '<input type="text" name="bobot[]" id="bobotKpiProses_'+ (index+1) +'" style="width: 100%; text-align: right" required onkeyup="'+
                'numberOnly(this.value, this.id);'+
                'CekBobotKpiProses(this.id);'+
                'setNilaiKpiProses(this.id);'+
            '">'+
        '</td>'+
        '<td><input type="text" style="width: 100%" id="targetKpiProses_'+ (index+1) +'" onkeyup="numberOnly(this.value, this.id);"></td>'+
        '<td style="text-align: center">'+
        '</td>'+
    '</tr>';

    $('#lastColumnKpiProses').before(kolom);
}