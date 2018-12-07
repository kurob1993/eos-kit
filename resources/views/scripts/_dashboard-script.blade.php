<script type="text/javascript">

    (handleConfirm = function () {
        $(document.body).on('submit', '.js-confirm', function (e) {
            // mengambil atribut data-confirm 
            var $el = $(this)
            var text = $el.data('confirm') ? $el.data('confirm') : 'Anda yakin melakukan tindakan ini?'
            // tampilkan pop up konfirmasi
            var c = confirm(text);
            // kondisi konfirmasi terkait tindakan
            if (c) {
                // menyimpan inputan catatan
                notes = prompt('Silahkan tulis catatan:');
                // jika klik cancel batalkan submit
                if (notes == null) {
                    // batalkan submit
                    e.preventDefault();
                } else {
                    // tambahkan data notes di dalam POST
                    var input = $('<input>').attr('type', 'hidden').attr('name', 'text').val(notes);
                    $el.append($(input));
                    // kirimkan submit
                    return true;
                }
            } else {
                // batalkan submit
                e.preventDefault();
            }
        });
    }),
    (handleSelectFilterDashboard = function () {
        function createSelectFilterHtml(tableId) {
            var selectHtml = '<form method="post" data-tablename='+ tableId +' id="form-' + tableId + '"> ' +
                '<select id="filter-' + tableId + '" data-tablename="'+ tableId +'" class="form-control"> ' +
                '<option value="all" selected>All</option>' +
                @foreach($stages as $stage)
                '<option value="{{ $stage->id }}">{{ $stage->description }}</option>' +
                @endforeach
                '</select>';
            $('#' + tableId).prev().html(selectHtml);
        }
        @foreach($tableNames as $t)
        createSelectFilterHtml('{{ $t }}');
        localStorage.setItem('state-{{ $t }}', 'all');
        @endforeach

        // registrasi change handler untuk select-filter
        $("[id^=filter-]").change(function () {
            // simpan state select di dalam local storage 
            var tablename = $(this).data('tablename');
            localStorage.setItem('state-' + tablename, this.value);
            // re draw
            $("#form-" + tablename).submit();
        });
        // registrasi submit handler untuk filter-form
        $("[id^=form-]").on('submit', function (e) {
            var tableId =  $(this).data('tablename');
            // re draw datatables (reload konten tabel)
            window.LaravelDataTables[tableId].draw();
            // batalkan submit
            e.preventDefault();
        });

        function findLocalItems (query) {
            var i, results = [];
            for (i in localStorage) {
                if (localStorage.hasOwnProperty(i)) {
                    if (i.match(query) || (!query && typeof i === 'string')) {
                        value = localStorage.getItem(i);
                        results.push({key:i,val:value});
                    }
                }
            }
            return results;
        }

        var selectStates = findLocalItems('^state\-.+Table');
        selectStates.forEach(function (item) {
            var tableName = item.key.replace(/^state\-/, '');
            $('#filter-'+tableName).val(item.val);
            // re draw 
            $('#form-'+tableName).submit();
        })
    }),
    (DashboardPlugins = (function () {
        "use strict";
        return {
            init: function () {
                handleConfirm(), handleSelectFilterDashboard();
            }
        };
    })());
</script>