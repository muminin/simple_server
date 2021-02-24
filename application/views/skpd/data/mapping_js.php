<script>
    $(document).ready(function() {
        $("#tab_data").DataTable({
            "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sUrl": "<?php echo base_url("assets/skpd/vendors/datatables.net/datatable_lang_indo.json") ?>",
            },
        });

        $(document).on("click", ".select-data", function() {
            var id = $(this).data("id");
            var input = "";

            if (this.checked) {
                input = "<input class='selected' type='hidden' name='data[]' id='data_" + id + "' value='" + id + "'>";
                $("#tambah").append(input);
            } else {
                $("#data_" + id).remove();
            }
        });

        $(document).on("click", ".view-kegiatan", function() {
            var kegiatan = $(this).data("kegiatan");

            $("#nama_kegiatan").html(kegiatan);
            $("#modal_view").modal('toggle');
        });

        $("#btn_simpan").click(function() {
            var count = 0;
            $(".selected").each(function(i, obj) {
                count++;
            });

            if (count > 0) {
                $("#tambah").submit();
            } else {
                showDangerToast("Harap pilih salah satu data.");
            }
        });

        $("#tambah").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: "<?php echo base_url("skpd/Data/mapping_data"); ?>",
                type: "post",
                data: $("#tambah").serialize(),
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data > 0) {
                        showSuccessToast("Data berhasil disimpan.",
                            "<?php echo base_url("skpd/Kegiatan?tahun=" . $kegiatan["jenis_tahun"]); ?>");
                    } else {
                        $(".loading").hide();
                    }
                },
            });
        });
    });
</script>