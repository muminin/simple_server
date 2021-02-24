<script>
    $(document).ready(function() {
        $("#parent").select2();

        var uraian = $("#uraian");

        uraian.keyup(function() {
            validation_text("uraian", "Uraian harus diisi");
        });

        $("#tambah").submit(function(event) {
            event.preventDefault();
            var check = ["uraian"];

            if (validation_check(check) > 0) {
                validation_text("uraian", "Uraian harus diisi");
            } else {
                $.ajax({
                    url: "<?php echo base_url("skpd/Data/ubah?level=" . $data["level"]); ?>",
                    type: "post",
                    data: $("#tambah").serialize(),
                    beforeSend: function(x) {
                        $(".loading").show();
                    },
                    success: function(data) {
                        if (data == 1) {
                            // showSuccessToast("Data berhasil disimpan.",
                            //     "<?php echo base_url("skpd/Data"); ?>");

                            showSuccessToast("Data berhasil disimpan.",
                                "<?php echo base_url("skpd/Data?tahun=" . $data["tahun"] . "&id_parent=" . $data["id_parent"] . "&level=" . $data["level"]); ?>");
                        } else {
                            $(".loading").hide();
                        }
                    },
                })
            }

        });
    });
</script>