<script>
    $(document).ready(function() {
        function clearBread(tipe) {
            if (tipe == "jenis") {
                $("#l_urusan").hide();
                $("#l_bidang").hide();
                $("#l_program").hide();
                $("#l_data").hide();
            } else if (tipe == "urusan") {
                $("#l_bidang").hide();
                $("#l_program").hide();
                $("#l_data").hide();
            } else if (tipe == "bidang") {
                $("#l_program").hide();
                $("#l_data").hide();
            } else if (tipe == "program") {
                $("#l_data").hide();
            }
        }

        function removeActiveBread(tipe) {
            if (tipe == "jenis") {
                $("#l_urusan").removeClass("active");
                $("#l_bidang").removeClass("active");
                $("#l_program").removeClass("active");
                $("#l_data").removeClass("active");

                $("#l_jenis").addClass("active");
            } else if (tipe == "urusan") {
                $("#l_jenis").removeClass("active");
                $("#l_bidang").removeClass("active");
                $("#l_program").removeClass("active");
                $("#l_data").removeClass("active");

                $("#l_urusan").addClass("active");
            } else if (tipe == "bidang") {
                $("#l_jenis").removeClass("active");
                $("#l_urusan").removeClass("active");
                $("#l_program").removeClass("active");
                $("#l_data").removeClass("active");

                $("#l_bidang").addClass("active");
            } else if (tipe == "program") {
                $("#l_jenis").removeClass("active");
                $("#l_urusan").removeClass("active");
                $("#l_bidang").removeClass("active");
                $("#l_data").removeClass("active");

                $("#l_program").addClass("active");
            } else if (tipe == "data") {
                $("#l_jenis").removeClass("active");
                $("#l_urusan").removeClass("active");
                $("#l_bidang").removeClass("active");
                $("#l_program").removeClass("active");

                $("#l_data").addClass("active");
            }
        }

        // GET JENIS
        $.ajax({
            url: "<?php echo base_url("skpd/Home/get_jenistahun") ?>",
            type: "post",
            data: {
                tahun: $("#tahun").val()
            },
            beforeSend: function() {
                $(".loading").show();
            },
            success: function(data) {
                $(".loading").hide();
                data = JSON.parse(data);

                var result = "<tbody id='data_body'>";
                data.forEach(function(item) {
                    result += "<tr class='select-jenis' data-id='" + item.id + "' data-name='" + item.nama_jenis_utama + "' style='cursor: pointer;'>";
                    result += "<td>";
                    result += item.nama_jenis_utama;
                    result += "</td>";
                    result += "</tr>";
                });
                result += "</tbody>";

                $("#tab_landing").append(result);
            },
        });

        $("#tahun").change(function() {
            var tahun_selected = $(this).val();
            $("#a_jenis").html("JENIS");
            clearBread("jenis");

            $.ajax({
                url: "<?php echo base_url("skpd/Home/get_jenistahun") ?>",
                type: "post",
                data: {
                    tahun: tahun_selected,
                },
                beforeSend: function() {
                    $(".loading").show();
                },
                success: function(data) {
                    $(".loading").hide();
                    data = JSON.parse(data);

                    $("#data_body").remove();

                    var result = "<tbody id='data_body'>";
                    data.forEach(function(item) {
                        result += "<tr class='select-jenis' data-id='" + item.id + "' data-name='" + item.nama_jenis_utama + "' style='cursor: pointer;'>";
                        result += "<td>";
                        result += item.nama_jenis_utama;
                        result += "</td>";
                        result += "</tr>";
                    });
                    result += "</tbody>";

                    $("#tab_landing").append(result);
                },
            });
        });

        // GET URUSAN
        $("#tab_landing").on("click", ".select-jenis", function() {
            var id = $(this).attr("data-id");
            var name = $(this).attr("data-name");
            $("#l_jenis").attr("data-id", id);
            $("#a_jenis").html(name);

            removeActiveBread("urusan");

            $.ajax({
                url: "<?php echo base_url("skpd/Home/get_urusan") ?>",
                type: "post",
                data: {
                    jenis: id,
                },
                beforeSend: function() {
                    $(".loading").show();
                },
                success: function(data) {
                    $(".loading").hide();
                    data = JSON.parse(data);

                    $("#data_body").remove();

                    var result = "<tbody id='data_body'>";
                    data.forEach(function(item) {
                        result += "<tr class='select-urusan' data-id='" + item.id + "' data-jenis='" + item.id_jenis_utama + "' data-name='" + item.nm_urusan + "' style='cursor: pointer;'>";
                        result += "<td>";
                        result += item.nm_urusan;
                        result += "</td>";
                        result += "</tr>";
                    });
                    result += "</tbody>";

                    $("#tab_landing").append(result);
                    $("#l_urusan").show();
                },
            });
        });

        // GET BIDANG
        $("#tab_landing").on("click", ".select-urusan", function() {
            var id = $(this).attr("data-id");
            var jenis = $(this).attr("data-jenis");
            var name = $(this).attr("data-name");
            $("#l_urusan").attr("data-id", jenis);
            $("#a_urusan").html(name);

            removeActiveBread("bidang");

            $.ajax({
                url: "<?php echo base_url("skpd/Home/get_bidang") ?>",
                type: "post",
                data: {
                    urusan: id,
                },
                beforeSend: function() {
                    $(".loading").show();
                },
                success: function(data) {
                    $(".loading").hide();
                    data = JSON.parse(data);

                    $("#data_body").remove();

                    var result = "<tbody id='data_body'>";
                    data.forEach(function(item) {
                        result += "<tr class='select-bidang' data-id='" + item.id + "' data-urusan='" + item.id_urusan + "' data-name='" + item.nm_bidang + "' style='cursor: pointer;'>";
                        result += "<td>";
                        result += item.nm_bidang;
                        result += "</td>";
                        result += "</tr>";
                    });
                    result += "</tbody>";

                    $("#tab_landing").append(result);
                    $("#l_bidang").show();
                },
            });
        });

        // GET PROGRAM
        $("#tab_landing").on("click", ".select-bidang", function() {
            var id = $(this).attr("data-id");
            var urusan = $(this).attr("data-urusan");
            var name = $(this).attr("data-name");
            $("#l_bidang").attr("data-id", urusan);
            $("#a_bidang").html(name);

            removeActiveBread("program");

            $.ajax({
                url: "<?php echo base_url("skpd/Home/get_program") ?>",
                type: "post",
                data: {
                    bidang: id,
                },
                beforeSend: function() {
                    $(".loading").show();
                },
                success: function(data) {
                    $(".loading").hide();
                    data = JSON.parse(data);

                    $("#data_body").remove();

                    var result = "<tbody id='data_body'>";
                    data.forEach(function(item) {
                        result += "<tr class='select-program' data-id='" + item.id + "' data-bidang='" + item.id_bidang + "' data-name='" + item.nm_program + "' style='cursor: pointer;'>";
                        result += "<td>";
                        result += item.nm_program;
                        result += "</td>";
                        result += "</tr>";
                    });
                    result += "</tbody>";

                    $("#tab_landing").append(result);
                    $("#l_program").show();
                },
            });
        });

        // GET DATA
        $("#tab_landing").on("click", ".select-program", function() {
            var id = $(this).attr("data-id");
            var bidang = $(this).attr("data-bidang");
            var name = $(this).attr("data-name");
            $("#l_program").attr("data-id", bidang);
            $("#a_program").html(name);

            removeActiveBread("data");

            $.ajax({
                url: "<?php echo base_url("skpd/Home/get_data") ?>",
                type: "post",
                data: {
                    program: id,
                },
                beforeSend: function() {
                    $(".loading").show();
                },
                success: function(data) {
                    $(".loading").hide();
                    var data = JSON.parse(data);
                    var level = data.level;
                    data = data.data

                    $("#data_body").remove();

                    var result = "<tbody id='data_body'>";
                    result += "<tr>";
                    result += "<th class='text-center'>Uraian</th>";
                    result += "<th class='text-center' style='width: 100px;'>Nilai</th>";
                    result += "<th class='text-center' style='width: 100px;'>Satuan</th>";
                    result += "</tr>";
                    for (var i = 1; i <= level; i++) {
                        $.each(data, function(key, item) {
                            $.each(item, function(key2, item2) {
                                if (item2.level == i && item2.level == 1) {
                                    result += "<tr class='select-data' id='row_" + item2.id + "' data-level='" + item2.level + "' data-id='" + item2.id + "' data-program='" + item2.id_program + "' data-name='" + item2.uraian + "' style='cursor: pointer;'>";
                                    result += "<td class='strong'>" + item2.uraian + "</td>";
                                    result += "<td id='nilai_" + item2.id + "' class='strong' data-current='0'>" + item2.nilai + "</td>";
                                    result += "<td class='strong'>" + item2.satuan + "</td>";
                                    result += "</tr>";
                                }
                            });
                        });
                    }
                    result += "</tbody>";

                    $("#tab_landing").append(result);
                    $("#l_data").show();

                    var tab = 0;
                    for (var i = 1; i <= level; i++) {
                        $.each(data, function(key, item) {
                            item.sort(function(a, b) {
                                return b.id - a.id
                            });

                            $.each(item, function(key2, item2) {
                                if (item2.level == i && item2.level != 1) {
                                    if (item2.nilai > 0) {
                                        total = 0;
                                        if ($("#nilai_" + item2.id_parent).attr("data-current") == "0") {
                                            $("#nilai_" + item2.id_parent).attr("data-current", "1");
                                            total = parseInt(item2.nilai, 10);

                                            $("#nilai_" + item2.id_parent).html(total);
                                        } else {
                                            total = parseInt($("#nilai_" + item2.id_parent).html(), 10) + parseInt(item2.nilai, 10);
                                            console.log(item2.id_parent + ": " + total + "//" + item2.nilai);

                                            $("#nilai_" + item2.id_parent).html(total);
                                        }
                                    }

                                    if (i > 2) {
                                        tab = 20 * i;
                                    } else {
                                        tab = 20;
                                    }

                                    var tr_child = "";
                                    tr_child += "<tr class='select-data' id='row_" + item2.id + "' data-level='" + item2.level + "' data-id='" + item2.id + "' data-program='" + item2.id_program + "' data-name='" + item2.uraian + "' style='cursor: pointer;'>";
                                    tr_child += "<td><span style='margin-left: " + tab + "px;'>" + item2.uraian + "</span></td>";
                                    tr_child += "<td id='nilai_" + item2.id + "'>" + item2.nilai + "</td>";
                                    tr_child += "<td>" + item2.satuan + "</td>";
                                    tr_child += "</tr>";

                                    $("#row_" + item2.id_parent).after(tr_child);
                                }
                            });
                        });
                    }
                },
            });
        });

        // BREADCRUMB
        $("#l_jenis").click(function() {
            if (!$(this).hasClass("active")) {
                clearBread("jenis");
                $("#a_jenis").html("JENIS");
                $.ajax({
                    url: "<?php echo base_url("skpd/Home/get_jenistahun") ?>",
                    type: "post",
                    data: {
                        tahun: $("#tahun").val()
                    },
                    beforeSend: function() {
                        $(".loading").show();
                    },
                    success: function(data) {
                        $(".loading").hide();
                        $("#data_body").remove();
                        data = JSON.parse(data);

                        var result = "<tbody id='data_body'>";
                        data.forEach(function(item) {
                            result += "<tr class='select-jenis' data-id='" + item.id + "' data-name='" + item.nama_jenis_utama + "' style='cursor: pointer;'>";
                            result += "<td>";
                            result += item.nama_jenis_utama;
                            result += "</td>";
                            result += "</tr>";
                        });
                        result += "</tbody>";

                        $("#tab_landing").append(result);
                    },
                });
            }
        });

        $("#l_urusan").click(function() {
            if (!$(this).hasClass("active")) {
                clearBread("urusan");
                $("#a_urusan").html("URUSAN");
                var id = $(this).attr("data-id");

                $.ajax({
                    url: "<?php echo base_url("skpd/Home/get_urusan") ?>",
                    type: "post",
                    data: {
                        jenis: id,
                    },
                    beforeSend: function() {
                        $(".loading").show();
                    },
                    success: function(data) {
                        $(".loading").hide();
                        data = JSON.parse(data);

                        $("#data_body").remove();

                        var result = "<tbody id='data_body'>";
                        data.forEach(function(item) {
                            result += "<tr class='select-urusan' data-id='" + item.id + "' data-urusan='" + item.id_urusan + "' data-name='" + item.nm_urusan + "' style='cursor: pointer;'>";
                            result += "<td>";
                            result += item.nm_urusan;
                            result += "</td>";
                            result += "</tr>";
                        });
                        result += "</tbody>";

                        $("#tab_landing").append(result);
                        $("#l_urusan").show();
                    },
                });
            }
        });

        $("#l_bidang").click(function() {
            if (!$(this).hasClass("active")) {
                clearBread("bidang");
                $("#a_bidang").html("BIDANG");
                var id = $(this).attr("data-id");

                $.ajax({
                    url: "<?php echo base_url("skpd/Home/get_bidang") ?>",
                    type: "post",
                    data: {
                        urusan: id,
                    },
                    beforeSend: function() {
                        $(".loading").show();
                    },
                    success: function(data) {
                        $(".loading").hide();
                        data = JSON.parse(data);

                        $("#data_body").remove();

                        var result = "<tbody id='data_body'>";
                        data.forEach(function(item) {
                            result += "<tr class='select-bidang' data-id='" + item.id + "' data-urusan='" + item.id_urusan + "' data-name='" + item.nm_bidang + "' style='cursor: pointer;'>";
                            result += "<td>";
                            result += item.nm_bidang;
                            result += "</td>";
                            result += "</tr>";
                        });
                        result += "</tbody>";

                        $("#tab_landing").append(result);
                        $("#l_bidang").show();
                    },
                });
            }
        });

        $("#l_program").click(function() {
            if (!$(this).hasClass("active")) {
                clearBread("program");
                $("#a_program").html("PROGRAM");
                var id = $(this).attr("data-id");

                $.ajax({
                    url: "<?php echo base_url("skpd/Home/get_program") ?>",
                    type: "post",
                    data: {
                        bidang: id,
                    },
                    beforeSend: function() {
                        $(".loading").show();
                    },
                    success: function(data) {
                        $(".loading").hide();
                        data = JSON.parse(data);

                        $("#data_body").remove();

                        var result = "<tbody id='data_body'>";
                        data.forEach(function(item) {
                            result += "<tr class='select-program' data-id='" + item.id + "' data-bidang='" + item.id_bidang + "' data-name='" + item.nm_program + "' style='cursor: pointer;'>";
                            result += "<td>";
                            result += item.nm_program;
                            result += "</td>";
                            result += "</tr>";
                        });
                        result += "</tbody>";

                        $("#tab_landing").append(result);
                        $("#l_program").show();
                    },
                });
            }
        });
    });
</script>