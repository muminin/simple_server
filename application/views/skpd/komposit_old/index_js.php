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
                            '<tr class="group" style="background: #ededed;"><td colspan="3" style="padding-left: 10px !important;">' + group + '</td></tr>'
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
                            '<tr class="group" style="background: #ededed;"><td colspan="3" style="padding-left: 30px !important;">' + group + '</td></tr>'
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
                            '<tr class="group" style="background: #ededed;"><td colspan="3" style="padding-left: 50px !important;">' + group + '</td></tr>'
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
                            '<tr class="group" style="background: #ededed;"><td colspan="3" style="padding-left: 70px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });
            }
        });

        $(document).on("click", ".edit", function() {
            var id = $(this).attr("data-id");
            var tahun = $(this).attr("data-tahun");

            // var url = "skpd/Komposit/ubah?komposit=" + id + "&tahun=" + tahun;
            var url = "skpd/Komposit/ubah?komposit=" + id;
            window.location.href = "<?php echo base_url(); ?>" + url;
        });

        $(document).on("click", ".delete", function() {
            var id = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo base_url("skpd/Komposit/hapus?kegiatan=") ?>" + id,
                type: "get",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        showSuccessToast("Data Komposit berhasil dihapus.",
                            "<?php echo base_url("skpd/Komposit"); ?>");
                    } else {
                        $(".loading").hide();
                    }
                }
            });
        });

        $("#btn_add").click(function() {
            window.location.href = "<?php echo base_url("skpd/Komposit/tambah"); ?>";
        })

        $("#btn_filter").click(function() {
            var tahun = $("#tahun").val();
            var url = "tahun=" + tahun;

            window.location.href = "<?php echo base_url("skpd/Komposit?"); ?>" + url;
        });

        $(document).on("click", ".view", function() {
            var id = $(this).attr("data-id");
            var nama = "Rumus Komposit <small>(";
            nama += $(this).attr("data-nama") + ")</small>";

            $("#modal_label").html(nama);
            $("#nilai_show").html("");
            $.ajax({
                url: "<?php echo base_url('skpd/Komposit/get_rumus_byid?komposit='); ?>" + id,
                type: "get",
                success: function(result) {
                    var data = JSON.parse(result);

                    $("#rumus_show").html(data.rumus_view);
                    $("#nilai_show").html(data.value_result);
                },
            });

            $('#exampleModal').modal('toggle');
        });
    });
</script>