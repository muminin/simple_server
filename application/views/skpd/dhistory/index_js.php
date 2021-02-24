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

        $(".selected-opd").click(function() {
            var id = $(this).attr("data-id");
            var data = $(this).attr("data-dataid");
            var nama = $(this).attr("data-nama");

            $("#modal_label").html("Data Saat Ini (" + nama + ")");
            $.ajax({
                url: "<?php echo base_url("skpd/Dhistory/get_data?data="); ?>" + data,
                type: "get",
                success: function(result) {
                    var data = JSON.parse(result);

                    $("#uraian").html(data.uraian);
                    $("#satuan").html(data.satuan);
                    $("#nilai").html(data.nilai);
                },
            });

            $('#exampleModal').modal('toggle');
        });

        // $("#simpan_bidang").click(function() {
        //     $.ajax({
        //         url: "<?php echo base_url('skpd/Konfigurasi/save_bidang/'); ?>",
        //         type: "post",
        //         data: $("#tambah").serialize(),
        //         success: function(data) {
        //             if (data == 1) {
        //                 $('#exampleModal').modal('toggle');
        //                 showSuccessToast("Data Konfigurasi berhasil disimpan.",
        //                     "<?php echo base_url("skpd/Konfigurasi"); ?>");
        //             }
        //         },
        //     });
        // })
    });
</script>