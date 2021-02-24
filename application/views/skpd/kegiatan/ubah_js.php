<script>
    $(document).ready(function() {
        $("#tahun").select2();
        $("#jenis_utama").select2();
        $("#urusan").select2();
        $("#bidang").select2();
        $("#program").select2();

        var jenis_utama = $("#jenis_utama");
        var urusan = $("#urusan");
        var kode = $("#kode");
        var nama = $("#nama");

        function clearData() {
            $("#kode").val("");
            $("#nama").val("");
        }

        kode.keyup(function() {
            validation_text("kode", "Kode harus diisi");
        });

        nama.keyup(function() {
            validation_text("nama", "Nama harus diisi");
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
                    if (item.id == <?php echo $kegiatan["urusan_id"]; ?>) {
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
                            if (item.id == <?php echo $kegiatan["bidang_id"]; ?>) {
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
                                    if (item.id == <?php echo $kegiatan["program_id"]; ?>) {
                                        selected = "selected";
                                    }

                                    $("#program").append("<option value='" + item.id + "' " + selected + ">" + item.nm_program + "</option>");
                                });

                                $(".loading").hide();
                            },
                        });
                    },
                });
            },
        });

        $("#tahun").change(function() {
            var tahun = $(this).val();
            change_jenis_utama(tahun, 1, 1, 1)
        });

        $("#jenis_utama").change(function() {
            var id = $(this).val();
            change_urusan(id, 1, 1);
        });

        $("#urusan").change(function() {
            var id = $(this).val();
            change_bidang(id, 1);
        });

        $("#bidang").change(function() {
            var id = $(this).val();
            change_program(id);
        });

        $("#tambah").submit(function(event) {
            event.preventDefault();
            var check = ["kode", "nama"];

            if (validation_check(check) > 0) {
                validation_text("kode", "Kode harus diisi");
                validation_text("nama", "Nama harus diisi");
            } else {
                $.ajax({
                    url: "<?php echo base_url("skpd/Kegiatan/ubah"); ?>",
                    type: "post",
                    data: $("#tambah").serialize(),
                    beforeSend: function(x) {
                        $(".loading").show();
                    },
                    success: function(data) {
                        if (data == 1) {
                            clearData();
                            showSuccessToast("Data Kegiatan berhasil disimpan.",
                                "<?php echo base_url("skpd/Kegiatan"); ?>");
                        } else {
                            $(".loading").hide();
                        }
                    },
                })
            }
        });
    });
</script>