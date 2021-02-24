<script>
    function clearData() {
        $("#nama").val("");
    }

    $(document).ready(function() {
        var nama = $("#nama");

        nama.keyup(function() {
            validation_text("nama", "Nama harus diisi");
        });

        $("#tambah").submit(function(event) {
            event.preventDefault();
            var check = ["nama"];

            if (validation_check(check) > 0) {
                validation_text("nama", "Nama harus diisi");
            } else {
                $.ajax({
                    url: "<?php echo base_url("skpd/Jenis_utama/tambah"); ?>",
                    type: "post",
                    data: $("#tambah").serialize(),
                    beforeSend: function(x) {
                        $(".loading").show();
                    },
                    success: function(data) {
                        if (data == 1) {
                            clearData();
                            showSuccessToast("Data Jenis Utama berhasil disimpan.",
                                "<?php echo base_url("skpd/Jenis_utama"); ?>");
                        } else {
                            $(".loading").hide();
                        }
                    },
                })
            }
        });
    });
</script>