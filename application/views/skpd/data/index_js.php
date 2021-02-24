<script>
    $(document).ready(function() {
        var select_filter = $("#filter").select2();
        select_filter.data("select2").$selection.css('height', '33px');
        select_filter.data("select2").$selection.css('padding', '10px');

        // var select_tahun = $("#tahun").select2();
        // select_tahun.data("select2").$selection.css('height', '33px');
        // select_tahun.data("select2").$selection.css('padding', '10px');

        $("#import_tahun").val("<?php echo $tahun_sess; ?>");

        $("#tahun").change(function() {
            var tahun = $(this).val();
            $.ajax({
                url: "<?php echo base_url("skpd/Program/get_program_bytahun") ?>",
                type: "post",
                data: {
                    tahun: tahun,
                },
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    data = JSON.parse(data);
                    // console.log(data);

                    $("#filter option").remove();
                    $("#filter").append("<option value='0'>Semua Data</option>");

                    data.forEach(function(item) {
                        $("#filter").append("<option value='" + item.id + "'>" + item.nm_program + "</option>");
                    });

                    $(".loading").hide();

                    $("#import_tahun").val(tahun);
                },
            })
        });

        $('#tab_program').DataTable({
            "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            "iDisplayLength": <?php echo (isset($_GET["program"]) || isset($_GET["bidang"]) || isset($_GET["urusan"])) ? -1 : 10; ?>,
            "oLanguage": {
                "sUrl": "<?php echo base_url("assets/skpd/vendors/datatables.net/datatable_lang_indo.json") ?>",
                // "sProcessing": $(".loading"),
            },
            // "processing": true,
            "columnDefs": [{
                    "visible": false,
                    "targets": 1
                },
                {
                    "visible": false,
                    "targets": 2
                },
                {
                    "visible": false,
                    "targets": 3
                },
                {
                    "visible": false,
                    "targets": 4
                },
                {
                    "visible": false,
                    "targets": 5
                },
                {
                    "visible": false,
                    "targets": 6
                },
                <?php if ($level > 1) {
                    for ($i = 1; $i < $level; $i++) { ?> {
                            "visible": false,
                            "targets": 6 + <?php echo $i; ?>,
                        },
                <?php }
                } ?>
            ],
            "drawCallback": function(settings) {
                // this function using for grouping rows
                var api = this.api();
                var rows = api.rows({
                    page: 'current'
                }).nodes();

                var last = null;
                api.column(1, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background: #ededed;"><td colspan="5" style="padding-left: 10px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });

                var last = null;
                api.column(2, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background: #ededed;"><td colspan="5" style="padding-left: 30px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });

                var last = null;
                api.column(3, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background: #ededed;"><td colspan="5" style="padding-left: 50px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });

                var last = null;
                api.column(4, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background: #ededed;"><td colspan="5" style="padding-left: 70px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });

                var last = null;
                api.column(5, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background: #ededed;"><td colspan="5" style="padding-left: 90px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });

                var last = null;
                api.column(6, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background: #ededed;"><td colspan="5" style="padding-left: 110px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });

                <?php if ($level > 1) {
                    for ($i = 1; $i < $level; $i++) { ?>
                        var padding = 110 + (20 * <?php echo $i; ?>);

                        api.column(6 + <?php echo $i; ?>, {
                            page: 'current'
                        }).data().each(function(group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before(
                                    '<tr class="group" style="background: #ededed;"><td colspan="5" style="padding-left: ' + padding + 'px !important;">' + group + '</td></tr>'
                                );

                                last = group;
                            }
                        });
                <?php }
                } ?>
            }
        });

        <?php if ($level > 1) { ?>
            $(".edit").click(function() {
                var id = $(this).attr("data-id");
                var level = $(this).attr("data-level");
                var url_ext = "id=" + id + "&level=" + level;

                // window.location.href = "<?php echo base_url("skpd/Data/ubah?"); ?>" + url_ext;
                window.open("<?php echo base_url("skpd/Data/ubah?"); ?>" + url_ext);
            });
        <?php } else { ?>
            $(".edit").click(function() {
                var id = $(this).attr("data-id");

                // window.location.href = "<?php echo base_url("skpd/Data/ubah?id="); ?>" + id;
                window.open("<?php echo base_url("skpd/Data/ubah?id="); ?>" + id);
            });
        <?php } ?>

        $(".delete").click(function() {
            var id = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo base_url("skpd/Data/hapus?id=") ?>" + id,
                type: "get",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        showSuccessToast("Data berhasil dihapus.",
                            "<?php echo base_url("skpd/Data"); ?>");
                    } else {
                        $(".loading").hide();
                    }
                }
            });
        });

        $(".add-data").click(function() {
            var id = $(this).attr("data-id");

            // window.location.href = "<?php echo base_url("skpd/Data/tambah_data?id="); ?>" + id;
            window.open("<?php echo base_url("skpd/Data/tambah_data?id="); ?>" + id);
        });

        $("#btn_filter").click(function() {
            // var program = $("#filter").val();
            var kegiatan = $("#filter").val();
            var tahun = $("#tahun").val();
            var url = "tahun=" + tahun;
            if (kegiatan != 0) {

                url += "&kegiatan=" + kegiatan;
                window.location.href = "<?php echo base_url("skpd/Data?"); ?>" + url;
            } else {
                window.location.href = "<?php echo base_url("skpd/Data?"); ?>" + url;
            }
        });

        $("#import_btn").click(function() {
            var tahun = $("#tahun").val();

            window.location.href = "<?php echo base_url("skpd/Export/data_byopd?tahun="); ?>" + tahun;
        });

        $("#upload_data").click(function() {
            var data = new FormData($("#import_form")[0]);

            $.ajax({
                url: "<?php echo base_url("skpd/Export/import_nilai") ?>",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: "post",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        showSuccessToast("Data berhasil dihapus.",
                            "<?php echo base_url("skpd/Data"); ?>");
                    } else {
                        $(".loading").hide();
                    }
                }
            });
        });
    });
</script>