<script type="text/javascript">
    if (typeof $ !== "undefined" && $.fn.dataTable) {
        var all_settings = $($.fn.dataTable.tables()).DataTable().settings();
        for (var i = 0, settings; (settings = all_settings[i]); ++i) {
            if (settings.jqXHR)
                settings.jqXHR.abort();
        }
    }
    </script>
    