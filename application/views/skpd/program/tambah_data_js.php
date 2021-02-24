<script>
    $(document).ready(function() {
        var global_count = 0;

        $("#tambah_data").click(function() {
            var number = 0;
            if ($('.delete-data')[0]) {
                number = $(".delete-data").length;
            }

            if ($("#count_data").val() != "") {
                var last_count = 0;
                var count = parseInt($("#count_data").val());

                global_count = global_count + count;
                last_count = global_count + count;

                var data_val = "";
                for (var i = global_count; i < last_count; i++) {
                    number++;

                    data_val += "<tr id='row_" + i + "'>";
                    data_val += "<td colspan='2'><input type='number' name='kode[]' id='kode_" + i + "' class='form-control form-control-sm kode' placeholder='Kode Kegiatan'></td>";
                    data_val += "<td><input type='text' name='nama[]' id='nama_" + i + "' class='form-control form-control-sm nama' placeholder='Nama Kegiatan'></td>";
                    data_val += "<td class='text-center'>";
                    data_val += "<button class='btn btn-sm btn-danger delete-data' id='" + i + "' data-id='" + i + "'><span class='ti-trash'></span></button>";
                    data_val += "</td>";
                    data_val += "</tr>";
                }

                $("#tab_data tbody").append(data_val);
            }

            $("#count_data").val("");
        });

        $(document).on("click", "button.delete-data", function() {
            var id = $(this).attr("id");
            $("#row_" + id).remove();
        });

        $("#btn_simpan").click(function(event) {
            event.preventDefault();

            var error = false;
            $(".kode").each(function(index) {
                if ($(this).val() == "") {
                    error = true;
                }
            });

            if (error) {
                showDangerToast("Isi semua kolom.");
                return false;
            } else {
                $("#tambah").submit();
            }

        });

        $("#tambah").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "<?php echo base_url("skpd/Program/tambah_data"); ?>",
                type: "post",
                data: $("#tambah").serialize(),
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        showSuccessToast("Data berhasil disimpan.",
                            "<?php echo base_url("skpd/Kegiatan"); ?>");
                    } else {
                        $(".loading").hide();
                    }
                },
            });
        });
    });
</script>