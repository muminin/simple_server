<script>
    $(document).ready(function() {
        $("#jenis_utama").select2();
        $("#urusan").select2();
        $("#bidang").select2();
        $("#program").select2();
        $("#kegiatan").select2();

        var uraian = $("#uraian");

        function clearData() {
            uraian.val("");
        }

        uraian.keyup(function() {
            validation_text("uraian", "Uraian harus diisi");
        });

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

                var selected;
                $("#urusan option").remove();
                data.forEach(function(item) {
                    selected = "";
                    if (item.id == <?php echo $data["urusan_id"]; ?>) {
                        selected = "selected";
                    }

                    $("#urusan").append("<option value='" + item.id + "' " + selected + ">" + item.nm_urusan + "</option>");
                });

                $(".loading").hide();
                $.ajax({
                    url: "<?php echo base_url("skpd/Bidang/get_bidang_byurusan") ?>",
                    type: "post",
                    data: {
                        urusan: $("#urusan").val(),
                    },
                    beforeSend: function(x) {
                        $(".loading").show();
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        var selected;
                        $("#bidang option").remove();
                        data.forEach(function(item) {
                            selected = "";
                            if (item.id == <?php echo $data["bidang_id"]; ?>) {
                                selected = "selected";
                            }

                            $("#bidang").append("<option value='" + item.id + "' " + selected + ">" + item.nm_bidang + "</option>");
                        });

                        $(".loading").hide();
                        $.ajax({
                            url: "<?php echo base_url("skpd/Program/get_program_bybidang") ?>",
                            type: "post",
                            data: {
                                bidang: $("#bidang").val(),
                            },
                            beforeSend: function(x) {
                                $(".loading").show();
                            },
                            success: function(data) {
                                data = JSON.parse(data);

                                var selected;
                                $("#program option").remove();
                                data.forEach(function(item) {
                                    selected = "";
                                    if (item.id == <?php echo $data["program_id"]; ?>) {
                                        selected = "selected";
                                    }

                                    $("#program").append("<option value='" + item.id + "' " + selected + ">" + item.nm_program + "</option>");
                                });

                                $(".loading").hide();
                                $.ajax({
                                    url: "<?php echo base_url("skpd/Kegiatan/get_kegiatan_byprogram") ?>",
                                    type: "post",
                                    data: {
                                        program: $("#program").val(),
                                    },
                                    beforeSend: function(x) {
                                        $(".loading").show();
                                    },
                                    success: function(data) {
                                        data = JSON.parse(data);

                                        var selected;
                                        $("#kegiatan option").remove();
                                        data.forEach(function(item) {
                                            selected = "";
                                            if (item.id == <?php echo $data["kegiatan_id"]; ?>) {
                                                selected = "selected";
                                            }

                                            $("#kegiatan").append("<option value='" + item.id + "' " + selected + ">" + item.nama + "</option>");
                                        });

                                        $(".loading").hide();
                                    },
                                });
                            },
                        });
                    },
                });
            },
        });

        $("#tahun").change(function() {
            var tahun = $(this).val();
            change_jenis_utama(tahun, 1, 1, 1, 1)
        });

        $("#jenis_utama").change(function() {
            var id = $(this).val();
            change_urusan(id, 1, 1, 1);
        });

        $("#urusan").change(function() {
            var id = $(this).val();
            change_bidang(id, 1, 1);
        });

        $("#bidang").change(function() {
            var id = $(this).val();
            change_program(id, 1);
        });

        $("#program").change(function() {
            var id = $(this).val();
            change_kegiatan(id);
        });

        $("#tambah").submit(function(event) {
            event.preventDefault();
            var check = ["jenis_utama", "urusan", "bidang", "program", "kegiatan", "uraian"];

            if (validation_check(check) > 0) {
                validation_text("jenis_utama", "Jenis Utama harus diisi");
                validation_text("urusan", "Urusan harus diisi");
                validation_text("bidang", "Bidang harus diisi");
                validation_text("program", "Program harus diisi");
                validation_text("kegiatan", "Kegiatan harus diisi");
                validation_text("uraian", "Uraian harus diisi");
            } else {
                $.ajax({
                    url: "<?php echo base_url("skpd/Data/ubah"); ?>",
                    type: "post",
                    data: $("#tambah").serialize(),
                    beforeSend: function(x) {
                        $(".loading").show();
                    },
                    success: function(data) {
                        if (data > 0) {
                            clearData();
                            // showSuccessToast("Data berhasil disimpan.",
                            //     "<?php echo base_url("skpd/Data"); ?>");

                            showSuccessToast("Data berhasil disimpan.",
                                "<?php echo base_url("skpd/Data?program="); ?>" + data);
                        } else {
                            $(".loading").hide();
                        }
                    },
                })
            }

        });
    });
</script>