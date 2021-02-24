<script>
    $(document).ready(function() {
        var global_count = 0;
        $("#jenis_utama").select2();
        $("#urusan").select2();
        $("#bidang").select2();

        $.ajax({
            url: "<?php echo base_url("skpd/Urusan/get_urusan_byjenis") ?>",
            type: "post",
            data: {
                jenis: $("#jenis_utama").val(),
            },
            beforeSend: function(x) {
                $(".loading").show();
            },
            success: function(data) {
                data = JSON.parse(data);

                var temp_index = 0;
                var selected;
                $("#urusan option").remove();
                data.forEach(function(item) {
                    selected = "";
                    if (temp_index == 0) {
                        temp_index = item.id;
                        selected = "selected";
                    }

                    $("#urusan").append("<option value='" + item.id + "' " + selected + ">" + item.nm_urusan + "</option>");

                    $.ajax({
                        url: "<?php echo base_url("skpd/Bidang/get_bidang_byurusan") ?>",
                        type: "post",
                        data: {
                            urusan: $("#urusan").val(),
                        },
                        success: function(data) {
                            data = JSON.parse(data);

                            $("#bidang option").remove();
                            data.forEach(function(item) {
                                $("#bidang").append("<option value='" + item.id + "'>" + item.nm_bidang + "</option>");
                            });
                        },
                    });
                });

                $(".loading").hide();
            },
        });

        $("#tahun").change(function() {
            var tahun = $(this).val();
            change_jenis_utama(tahun, 1, 1)
        });

        $("#jenis_utama").change(function() {
            var id = $(this).val();
            change_urusan(id, 1);
        });

        $("#urusan").change(function() {
            var id = $(this).val();
            change_bidang(id, 1);
        });

        $("#bidang").change(function() {
            var id = $(this).val();
            change_program(id);
        });

        $("#level").change(function() {
            var id = $(this).val();
            if (id > 0) {
                $("#data_list").show();
            } else {
                $("#data_list").hide();
            }
        });

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
                // console.log(global_count + "//" + last_count);

                var data_val = "";
                for (var i = global_count; i < last_count; i++) {
                    number++;

                    data_val += "<tr id='row_" + i + "'>";
                    // data_val += "<td>" + number + "</td>";
                    data_val += "<td><input type='text' name='nama[]' id='nama_" + i + "' class='form-control form-control-sm' placeholder='Nama'></td>";
                    data_val += "<td><input type='text' name='satuan[]' id='satuan_" + i + "' class='form-control form-control-sm' placeholder='Satuan'></td>";
                    data_val += "<td><input type='text' name='uraian[]' id='uraian_" + i + "' class='form-control form-control-sm' placeholder='Uraian'></td>";
                    data_val += "<td><input type='text' name='kecamatan[]' id='kecamatan_" + i + "' class='form-control form-control-sm' placeholder='Kecamatan'></td>";
                    data_val += "<td><input type='text' name='nilai[]' id='nilai_" + i + "' class='form-control form-control-sm' placeholder='Nilai'></td>";
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

        $("#data").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "<?php echo base_url("skpd/Data/tambah"); ?>",
                type: "post",
                data: $("#data").serialize(),
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        clearData();
                        showSuccessToast("Data berhasil disimpan.",
                            "<?php echo base_url("skpd/Data"); ?>");
                    } else {
                        $(".loading").hide();
                    }
                },
            })
        });
    });
</script>