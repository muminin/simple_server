<script>
    $(document).ready(function() {
        $("#jenis_utama").select2();
        $("#urusan").select2();

        var jenis_utama = $("#jenis_utama");
        var urusan = $("#urusan");
        var kode = $("#kode");
        var nama = $("#nama");

        function clearData() {
            $("#kode").val("");
            $("#nama").val("");
        }

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
                console.log(data);

                var selected;
                $("#urusan option").remove();
                data.forEach(function(item) {
                    selected = "";
                    if (item.id == <?php echo $bidang["urusan_id"]; ?>) {
                        selected = "selected";
                    }

                    $("#urusan").append("<option value='" + item.id + "' " + selected + ">" + item.nm_urusan + "</option>");
                });

                $(".loading").hide();
            },
        });

        $("#tahun").change(function() {
            var tahun = $(this).val();
            change_jenis_utama(tahun, 1)
        });

        $("#jenis_utama").change(function() {
            var id = $(this).val();
            change_urusan(id);
        });

        $("#tambah").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "<?php echo base_url("skpd/Bidang/ubah"); ?>",
                type: "post",
                data: $("#tambah").serialize(),
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        clearData();
                        showSuccessToast("Data Bidang berhasil disimpan.",
                            "<?php echo base_url("skpd/Bidang"); ?>");
                    } else {
                        $(".loading").hide();
                    }
                },
            })
        });
    });
</script>