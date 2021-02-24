<script>
    $(document).ready(function() {
        $('#tab_jenis').DataTable({
            "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sUrl": "<?php echo base_url("assets/skpd/vendors/datatables.net/datatable_lang_indo.json") ?>",
            },
        });

        $(document).on("click", ".print", function() {
            var programId = $(this).data("program");

            // $(".loading").show();
            window.location.href = "<?php echo base_url("skpd/Export/history_byprogram?program=") ?>" + programId;
        });
    });
</script>