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
        // membuat filter dan tombol approve & deny all
        function createSelectFilterHtml(tableId, approval) {
            var selectHtml = 
                '<div class="col-md-6 col-xs-12 m-b-10">'
                + '<div class="col-xs-6">'
                + '<form data-confirm="Yakin menyetujui semua?" class="js-confirm" method="post" action="{{ route('dashboards.approve_all') }}">'
                + '{{ @csrf_field() }}'
                + '<input type="hidden" name="approval" value="' + approval + '">'
                + '<button class="btn btn-primary btn-block">'
                + '<i class="fa fa-check" aria-hidden="true"></i>&nbsp;Setujui semua</a>'
                + '</button>'
                + '</form>'
                + '</div>'
                + '<div class="col-xs-6">'
                {{-- + '<form data-confirm="Yakin menolak semua?" class="js-confirm" method="post" action="{{ route('dashboards.reject_all') }}">'
                + '{{ @csrf_field() }}'
                + '<input type="hidden" name="approval" value="' + approval + '">'
                + '<button class="btn btn-danger btn-block">'
                + '<i class="fa fa-ban" aria-hidden="true"></i>&nbsp;Tolak semua</a>'
                + '</button>'
                + '</form>' --}}
                + '</div>'
                + '</div>'
                + '<div class="col-md-6 col-xs-12">'
                +' <form method="post" data-tablename='+ tableId +' id="form-' + tableId + '"> ' 
                + '<select id="filter-' + tableId + '" data-tablename="'+ tableId +'" class="form-control"> ' 
                + '<option value="all" selected>All</option>'
                @foreach($stages as $stage)
                + '<option value="{{ $stage->id }}">{{ $stage->description }}</option>'
                @endforeach
                + '</select></form>'
                + '</div>';
            // mengisi div.toolbar
            $('#' + tableId).prev().html(selectHtml);
        }

        // menyimpan select filter di local storage
        @foreach($tableNames as $t)
        createSelectFilterHtml('{{ $t['tableName'] }}', '{{ $t['approval'] }}');
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

        // function mencari local storage berdasarkan string atau regex
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