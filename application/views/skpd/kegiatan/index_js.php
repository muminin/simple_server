<script>
    $(document).ready(function() {
        // var select_tahun = $("#tahun").select2();
        // select_tahun.data("select2").$selection.css('height', '33px');
        // select_tahun.data("select2").$selection.css('padding', '10px');

        $("#import_tahun").val("<?php echo $tahun_sess; ?>");

        $('#tab_program').DataTable({
            "aLengthMenu": [
                [5, 10, 15, -1],
                [5, 10, 15, "All"]
            ],
            "iDisplayLength": 10,
            "oLanguage": {
                "sUrl": "<?php echo base_url("assets/skpd/vendors/datatables.net/datatable_lang_indo.json") ?>",
            },
            "columnDefs": [{
                "visible": false, // hide the main grouping head rows trigger
                "targets": 1
            }, {
                "visible": false,
                "targets": 2
            }, {
                "visible": false,
                "targets": 3
            }, {
                "visible": false,
                "targets": 4
            }, {
                "visible": false,
                "targets": 5
            }, ],
            "drawCallback": function(settings) {
                // this function using for grouping rows
                var api = this.api();
                var last = null;
                var rows = api.rows({
                    page: 'current'
                }).nodes();

                api.column(1, {
                    page: 'current'
                }).data().each(function(group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group" style="background: #ededed;"><td class="break-word" colspan="4" style="padding-left: 10px !important;">' + group + '</td></tr>'
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
                            '<tr class="group" style="background: #ededed;"><td class="break-word" colspan="4" style="padding-left: 30px !important;">' + group + '</td></tr>'
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
                            '<tr class="group" style="background: #ededed;"><td class="break-word" colspan="4" style="padding-left: 50px !important;">' + group + '</td></tr>'
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
                            '<tr class="group" style="background: #ededed;"><td class="break-word" colspan="4" style="padding-left: 70px !important;">' + group + '</td></tr>'
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
                            '<tr class="group" style="background: #ededed;"><td class="break-word" colspan="4" style="padding-left: 90px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });
            }
        });

        $(".edit").click(function() {
            var id = $(this).attr("data-id");
            window.location.href = "<?php echo base_url("skpd/Kegiatan/ubah?kegiatan="); ?>" + id;
        });

        $(".delete").click(function() {
            var id = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo base_url("skpd/Kegiatan/hapus?kegiatan=") ?>" + id,
                type: "get",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        showSuccessToast("Data Kegiatan berhasil dihapus.",
                            "<?php echo base_url("skpd/Kegiatan"); ?>");
                    } else {
                        $(".loading").hide();
                    }
                }
            });
        });

        $(document).on("click", ".add-data", function() {
            var id = $(this).data("id");

            window.location.href = "<?php echo base_url("skpd/Kegiatan/tambah_data?kegiatan="); ?>" + id;
        })

        $("#btn_filter").click(function() {
            var tahun = $("#tahun").val();
            var url = "tahun=" + tahun;

            window.location.href = "<?php echo base_url("skpd/Kegiatan?"); ?>" + url;
        });

        $(document).on("click", ".mapping-data", function() {
            var id = $(this).data("id");
            window.location.href = "<?php echo base_url("skpd/Data/mapping_data?kegiatan=") ?>" + id;
        });

        $("#import_btn").click(function() {
            var tahun = $("#tahun").val();
            var url = "<?php echo base_url("uploads/template/template_kegiatan.xlsx"); ?>";

            window.location.href = url;
        });

        $("#upload_data").click(function() {
            var data = new FormData($("#import_form")[0]);

            $.ajax({
                url: "<?php echo base_url("skpd/Export/import_kegiatan") ?>",
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
                        showSuccessToast("Data berhasil diimpor.",
                            "<?php echo base_url("skpd/Kegiatan"); ?>");
                    } else {
                        $(".loading").hide();
                    }

                    $(".loading").hide();
                }
            });
        });
    });
</script>