<script>
    function clearData() {
        $("#kode").val("");
        $("#nama").val("");
    }

    $(document).ready(function() {
        $("#jenis_utama").select2();

        var kode = $("#kode");
        var nama = $("#nama");

        kode.keyup(function() {
            validation_text("kode", "Kode harus diisi");
        });

        nama.keyup(function() {
            validation_text("nama", "Nama harus diisi");
        });

        $("#tahun").change(function() {
            var tahun = $(this).val();
            change_jenis_utama(tahun);
        });

        $("#tambah").submit(function(event) {
            event.preventDefault();
            var check = ["kode", "nama"];

            if (validation_check(check) > 0) {
                validation_text("kode", "Kode harus diisi");
                validation_text("nama", "Nama harus diisi");
            } else {
                $.ajax({
                    url: "<?php echo base_url("skpd/Urusan/tambah"); ?>",
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