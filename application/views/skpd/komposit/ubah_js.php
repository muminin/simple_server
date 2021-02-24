<?php
$group_id = $this->session->userdata("group");
?>

<script>
    $(document).ready(function() {
        var jenis_utama = $("#jenis_utama");

        $("#tahun").select2();
        $("#bidang").select2();

        $.ajax({
            url: "<?php echo base_url("skpd/Komposit/get_bidang_bytahun?tahun="); ?>" + $("#tahun").val(),
            type: "get",
            beforeSend: function(x) {
                $(".loading").show();
            },
            success: function(data) {
                data = JSON.parse(data);

                $("#bidang option").remove();
                $("#bidang").append("<option value=''>-- Pilih Bidang</option>");
                data.forEach(function(item) {
                    var selected = "";
                    if (item.id == <?php echo $komposit["bidang_id"] ?>) {
                        selected = "selected";
                    }

                    $("#bidang").append("<option value='" + item.id + "' " + selected + ">" + item.nm_bidang + "</option>");
                });

                $(".loading").hide();
            },
        });

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
            });
        });

        $("#simpan").click(function() {
            event.preventDefault();
            var check = ["tahun", "bidang", "uraian", "satuan"];
            var tahun = $("#tahun").val();

            <?php if ($group_id != 1) { ?>
                check.push("nilai");
            <?php } ?>

            if (validation_check(check) > 0) {
                validation_text("tahun", "Tahun harus dipilih");
                validation_text("bidang", "Bidang harus dipilih");
                validation_text("uraian", "Uraian harus diisi");
                validation_text("satuan", "Satuan harus diisi");

                <?php if ($group_id != 1) { ?>
                    validation_text("nilai", "Nilai harus diisi meskipun 0");
                <?php } ?>
            } else {
                $.ajax({
                    url: "<?php echo base_url("skpd/Komposit/ubah"); ?>",
                    type: "post",
                    data: $("#ubah").serialize(),
                    beforeSend: function(x) {
                        $(".loading").show();
                    },
                    success: function(data) {
                        data = JSON.parse(data);

                        $(".loading").hide();
                        window.location.href = "<?php echo base_url("skpd/Komposit?tahun=") ?>" + tahun;
                    },
                });
            }
        });
    });
</script>