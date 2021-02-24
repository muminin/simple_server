<script>
    $(document).ready(function() {
        var select_tahun = $("#tahun").select2();
        select_tahun.data("select2").$selection.css('height', '33px');
        select_tahun.data("select2").$selection.css('padding', '10px');

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
            }
        });

        $(document).on("click", ".edit", function() {
            var id = $(this).attr("data-id");
            var tahun = $(this).attr("data-tahun");

            var url = "skpd/Komposit/ubah?komposit=" + id;
            window.location.href = "<?php echo base_url(); ?>" + url;
        });

        $(document).on("click", ".delete", function() {
            var id = $(this).attr("data-id");
            var tahun = $("#tahun").val();
            $.ajax({
                url: "<?php echo base_url("skpd/Komposit/hapus?komposit=") ?>" + id,
                type: "get",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        showSuccessToast("Data Komposit berhasil dihapus.",
                            "<?php echo base_url("skpd/Komposit?tahun="); ?>" + tahun);
                    } else {
                        $(".loading").hide();
                    }
                }
            });
        });

        $(document).on("click", ".salin-jenis", function() {
            var id = $(this).attr("data-id");
            var nama = $(this).attr("data-nama");

            $("#modal_label").html("Isi Nilai (" + nama + ")");
            $("#komposit").val(id);

            $('#nilai_mod').modal('toggle');
        });

        $("#simpan_nilai").click(function(event) {
            event.preventDefault();
            var check = ["nilai"];
            var tahun = $("#tahun").val();

            if (validation_check(check) > 0) {
                validation_text("nilai", "Nilai harus diisi meskipun 0");
            } else {
                $.ajax({
                    url: "<?php echo base_url("skpd/Komposit/isi_nilai"); ?>",
                    type: "post",
                    data: $("#nilai_form").serialize(),
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

        $("#btn_add").click(function() {
            window.location.href = "<?php echo base_url("skpd/Komposit/tambah"); ?>";
        })

        $("#btn_filter").click(function() {
            var tahun = $("#tahun").val();
            var url = "tahun=" + tahun;

            window.location.href = "<?php echo base_url("skpd/Komposit?"); ?>" + url;
        });
    });
</script>