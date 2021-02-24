<script>
    function clearData() {
        $("#kode").val("");
        $("#nama").val("");
    }

    $(document).ready(function() {
        var kode = $("#kode");
        var nama = $("#nama");

        kode.keyup(function() {
            validation_text("kode", "Kode harus diisi");
        });

        nama.keyup(function() {
            validation_text("nama", "Nama harus diisi");
        });

        $("#jenis_utama").select2();
        $("#tahun").select2();

        $("#tahun").change(function() {
            var tahun = $(this).val();

            $.ajax({
                url: "<?php echo base_url("skpd/Jenis_Utama/get_data_bytahun") ?>",
                type: "post",
                data: {
                    tahun: tahun,
                },
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    data = JSON.parse(data);
                    console.log(data);

                    $("#jenis_utama option").remove();
                    data.forEach(function(item) {
                        $("#jenis_utama").append("<option value='" + item.id + "'>" + item.nama_jenis_utama + "</option>");
                    });

                    $(".loading").hide();
                },
            })
        });

        $("#tambah").submit(function(event) {
            event.preventDefault();
            var check = ["kode", "nama"];

            if (validation_check(check) > 0) {
                validation_text("kode", "Kode harus diisi");
                validation_text("nama", "Nama harus diisi");
            } else {
                $.ajax({
                    url: "<?php echo base_url("skpd/Urusan/ubah"); ?>",
                    type: "post",
                    data: $("#tambah").serialize(),
                    beforeSend: function(x) {
                        $(".loading").show();
                    },
                    success: function(data) {
                        if (data == 1) {
                            clearData();
                            showSuccessToast("Data Urusan berhasil disimpan.",
                                "<?php echo base_url("skpd/Urusan"); ?>");
                        } else {
                            $(".loading").hide();
                        }
                    },
                })
            }
        });
    });
</script>