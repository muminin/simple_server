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
                    data_val += "<td colspan='2'><input type='text' name='uraian_c[]' id='uraian_" + i + "' class='form-control form-control-sm uraian_c' placeholder='Nama Data'></td>";
                    data_val += "<td><input type='text' name='satuan[]' id='satuan_" + i + "' class='form-control form-control-sm satuan' placeholder='Satuan'></td>";
                    data_val += "<td><input type='text' name='nilai[]' id='nilai_" + i + "' class='form-control form-control-sm nilai' placeholder='Nilai'></td>";
                    data_val += "<td class='text-center'>";
                    data_val += "<button class='btn btn-sm btn-danger delete-data' id='" + i + "' data-id='" + i + "'><span class='ti-trash'></span></button>";
                    data_val += "</td>";
                    data_val += "</tr>";
                }

                $("#tab_data tbody").append(data_val);
            }
        });

        $(document).on("click", "button.delete-data", function() {
            var id = $(this).attr("id");
            $("#row_" + id).remove();
        });

        $("#tambah").submit(function(event) {
            event.preventDefault();

            if ($("input").hasClass("uraian_c")) {
                var i = true;
                $(".uraian_c").each(function() {
                    if ($(this).val() == "") {
                        i = false;
                    }
                });

                if (i == false) {
                    showDangerToast("Harap isi semua Nama Data!");
                    return false;
                }
            }

            if ($("input").hasClass("satuan")) {
                var i = true;
                $(".satuan").each(function() {
                    if ($(this).val() == "") {
                        i = false;
                    }
                });

                if (i == false) {
                    showDangerToast("Harap isi semua Satuan!");
                    return false;
                }
            }

            $.ajax({
                url: "<?php echo base_url("skpd/Data/tambah_data"); ?>",
                type: "post",
                data: $("#tambah").serialize(),
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data > 0) {
                        showSuccessToast("Data berhasil disimpan.",
                            "<?php echo base_url("skpd/Data?id_parent=" . $data["id"] . "&level=" . ($data["level"] + 1)); ?>");

                        // showSuccessToast("Data berhasil disimpan.",
                        //     "<?php echo base_url("skpd/Data?program="); ?>" + data);
                    } else {
                        $(".loading").hide();
                    }
                },
            });
        });
    });
</script>