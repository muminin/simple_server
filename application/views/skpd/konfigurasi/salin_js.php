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

        $(".salin-jenis").click(function() {
            var id = $(this).attr("data-id");
            var tahun = $(this).attr("data-tahun");
            var nama_jenis = $(this).attr("data-jenis");

            $("#modal_label").html(nama_jenis + " (" + tahun + ")");
            $("#jenis").val(id);
            $('#exampleModal').modal('toggle');
        });

        $("#simpan").click(function() {
            $.ajax({
                url: "<?php echo base_url('skpd/Konfigurasi/copy_simple_bytahun/'); ?>",
                type: "post",
                data: $("#tambah").serialize(),
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    $(".loading").hide();

                    if (data == 1) {
                        $('#exampleModal').modal('toggle');
                        showSuccessToast("Data berhasil disimpan.",
                            "<?php echo base_url("skpd/Konfigurasi/copy_simple"); ?>");
                    } else if (data == 2) {
                        showDangerToast("Data pada Tahun tersebut sudah ada.");
                    }
                },
            });
        })
    });
</script>