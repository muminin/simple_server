<script>
    $(document).ready(function() {
        var jenis_utama = $("#jenis_utama");

        $("#tahun").select2();
        $("#bidang").select2();
        $("#data").select2();

        $("#tahun").change(function() {
            var tahun = $(this).val();

            $.ajax({
                url: "<?php echo base_url("skpd/Komposit/get_bidang_bytahun?tahun="); ?>" + tahun,
                type: "get",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    data = JSON.parse(data);

                    $("#bidang option").remove();
                    $("#bidang").append("<option value=''>-- Pilih Bidang</option>");
                    data.forEach(function(item) {
                        $("#bidang").append("<option value='" + item.id + "'>" + item.nm_bidang + "</option>");
                    });

                    $(".loading").hide();
                },
            })
        });

        $("#bidang").change(function() {
            var bidang = $(this).val();

            $.ajax({
                url: "<?php echo base_url("skpd/Komposit/get_data_bybidang?bidang="); ?>" + bidang,
                type: "get",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    data = JSON.parse(data);

                    $("#data option").remove();
                    $("#data").append("<option value=''>-- Pilih Data</option>");
                    data.forEach(function(item) {
                        $("#data").append("<option value='" + item.uniq + "'>" + item.uraian + "</option>");
                    });

                    $(".loading").hide();
                },
            });
        });

        $("#data").change(function() {
            var uniq = $(this).val();
            var text = $(this).find("option:selected").text();
            var rumus_show = $("#rumus_show").html();
            var rumus = $("#rumus").val();

            if (rumus_show != "") {
                rumus_show += " " + text;
                rumus += "||" + uniq;
            } else {
                rumus_show = text;
                rumus = uniq;
            }

            $("#rumus_show").html(rumus_show);
            $("#rumus").val(rumus);
        });

        $(".math").click(function(event) {
            event.preventDefault();

            var view = $(this).data("view");
            var math = $(this).data("math");
            var rumus_show = $("#rumus_show").html();
            var rumus = $("#rumus").val();

            if (math == "del") {
                rumus_show = "";
                rumus = "";
            } else {
                if (rumus_show != "") {
                    rumus_show += " <strong>" + view + "</strong>";
                    rumus += "||" + math;
                } else {
                    rumus_show = " <strong>" + view + "</strong>";
                    rumus = math;
                }
            }

            $("#rumus_show").html(rumus_show);
            $("#rumus").val(rumus);

            // $("#data").val("");
        });

        $("#in_rumus").change(function() {
            var value = $(this).val();
            var rumus_show = $("#rumus_show").html();
            var rumus = $("#rumus").val();

            if (rumus_show != "") {
                rumus_show += value;
                rumus += "||" + value;
            } else {
                rumus_show = value;
                rumus = value;
            }

            $("#rumus_show").html(rumus_show);
            $("#rumus").val(rumus);
        });

        $("#simpan").click(function() {
            $.ajax({
                url: "<?php echo base_url("skpd/Komposit/tambah"); ?>",
                type: "post",
                data: $("#tambah").serialize(),
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    data = JSON.parse(data);

                    $(".loading").hide();
                    window.location.href = "<?php echo base_url("skpd/Komposit") ?>";
                },
            });
        });





        // $("#tambah").submit(function(event) {
        //     event.preventDefault();
        //     var check = ["kode", "nama"];

        //     if (validation_check(check) > 0) {
        //         validation_text("kode", "Kode harus diisi");
        //         validation_text("nama", "Nama harus diisi");
        //     } else {
        //         $.ajax({
        //             url: "<?php echo base_url("skpd/Program/tambah"); ?>",
        //             type: "post",
        //             data: $("#tambah").serialize(),
        //             beforeSend: function(x) {
        //                 $(".loading").show();
        //             },
        //             success: function(data) {
        //                 if (data == 1) {
        //                     clearData();
        //                     showSuccessToast("Data Program berhasil disimpan.",
        //                         "<?php echo base_url("skpd/Program"); ?>");
        //                 } else {
        //                     $(".loading").hide();
        //                 }
        //             },
        //         })
        //     }
        // });

        // function clearData() {
        //     $("#kode").val("");
        //     $("#nama").val("");
        // }

        // kode.keyup(function() {
        //     validation_text("kode", "Kode harus diisi");
        // });

        // nama.keyup(function() {
        //     validation_text("nama", "Nama harus diisi");
        // });

        // $.ajax({
        //     url: "<?php echo base_url("skpd/Urusan/get_urusan_byjenis") ?>",
        //     type: "post",
        //     data: {
        //         jenis: $("#jenis_utama").val(),
        //     },
        //     beforeSend: function(x) {
        //         $(".loading").show();
        //     },
        //     success: function(data) {
        //         data = JSON.parse(data);

        //         var temp_index = 0;
        //         var selected;
        //         $("#urusan option").remove();
        //         data.forEach(function(item) {
        //             selected = "";
        //             if (temp_index == 0) {
        //                 temp_index = item.id;
        //                 selected = "selected";
        //             }

        //             $("#urusan").append("<option value='" + item.id + "' " + selected + ">" + item.nm_urusan + "</option>");

        //             $.ajax({
        //                 url: "<?php echo base_url("skpd/Bidang/get_bidang_byurusan") ?>",
        //                 type: "post",
        //                 data: {
        //                     urusan: $("#urusan").val(),
        //                 },
        //                 success: function(data) {
        //                     data = JSON.parse(data);

        //                     $("#bidang option").remove();
        //                     data.forEach(function(item) {
        //                         $("#bidang").append("<option value='" + item.id + "'>" + item.nm_bidang + "</option>");
        //                     });
        //                 },
        //             });
        //         });

        //         $(".loading").hide();
        //     },
        // });
    });
</script>