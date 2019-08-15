<script type="text/javascript">
    (handleSelectpicker = function() {
        
        function renderSelect2 (value) {
        if (!value.id) { return value.text; }
            var $value = $('<span class="label label-default bg-black">' + value.id + '</span> ' + value.text);
            return $value;
        };
        
        $('#selectJabatan').select2({
            theme: "bootstrap",
            minimumInputLength: 2,
            allowClear: true,
            templateResult: renderSelect2,
		    templateSelection: renderSelect2,
            ajax: {
                url: "{{ route('transition.StructJab') }}",
                dataType: 'json',
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
                cache: true
            }
        });

        $('#selectKaryawan').select2({
            theme: "bootstrap",
            minimumInputLength: 2,
            allowClear: true,
            templateResult: renderSelect2,
		    templateSelection: renderSelect2,
            ajax: {
                url: "{{ route('transition.employee') }}",
                dataType: 'json',
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
                cache: true
            }
        });
    }),
    (DatepickerPlugins = function() {
        $('.input-daterange').datepicker({
            format: "dd-mm-yyyy",
            orientation: "bottom auto" 
        });
    }),
    
    (TransitionPlugins = (function() {
        "use strict";
        return {
            init: function() {
                handleSelectpicker();
                DatepickerPlugins();
            }
        };
    })());
</script>