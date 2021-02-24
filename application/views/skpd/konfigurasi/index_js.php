<script>
    $(document).ready(function() {
        $("#bidang").select2();

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
            var desc = $(this).attr("data-desc");

            $("#modal_label").html(desc);
            $("#group").val(id);
            $.ajax({
                url: "<?php echo base_url('skpd/Konfigurasi/bidang_bygroup/'); ?>" + id,
                type: "post",
                success: function(result) {
                    var data = JSON.parse(result);
                    var select = [];
                    $.each(data, function(i, item) {
                        select.push(item.id_bidang);
                    });

                    $("#bidang").select2().val(select).trigger('change');
                },
            });

            $('#exampleModal').modal('toggle');
        });

        $("#simpan_bidang").click(function() {
            $.ajax({
                url: "<?php echo base_url('skpd/Konfigurasi/save_bidang/'); ?>",
                type: "post",
                data: $("#tambah").serialize(),
                success: function(data) {
                    if (data == 1) {
                        $('#exampleModal').modal('toggle');
                        showSuccessToast("Data Konfigurasi berhasil disimpan.",
                            "<?php echo base_url("skpd/Konfigurasi"); ?>");
                    }
                },
            });
        })
    });
</script>