<script>
    $(document).ready(function() {
        // var select_tahun = $("#tahun").select2();
        // select_tahun.data("select2").$selection.css('height', '33px');
        // select_tahun.data("select2").$selection.css('padding', '10px');

        $('#tab_bidang').DataTable({
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
                "visible": false, // hide the main grouping head rows trigger
                "targets": 2
            }, {
                "visible": false, // hide the main grouping head rows trigger
                "targets": 3
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
                            '<tr class="group" style="background: #ededed;"><td colspan="4" style="padding-left: 10px !important;">' + group + '</td></tr>'
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
                            '<tr class="group" style="background: #ededed;"><td colspan="4" style="padding-left: 30px !important;">' + group + '</td></tr>'
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
                            '<tr class="group" style="background: #ededed;"><td colspan="4" style="padding-left: 50px !important;">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });
            }
        });

        $(".edit").click(function() {
            var id = $(this).attr("data-id");
            window.location.href = "<?php echo base_url("skpd/Bidang/ubah?id="); ?>" + id;
        });

        $(".delete").click(function() {
            var id = $(this).attr("data-id");
            $.ajax({
                url: "<?php echo base_url("skpd/Bidang/hapus?id=") ?>" + id,
                type: "get",
                beforeSend: function(x) {
                    $(".loading").show();
                },
                success: function(data) {
                    if (data == 1) {
                        showSuccessToast("Data Bidang berhasil dihapus.",
                            "<?php echo base_url("skpd/Bidang"); ?>");
                    } else {
                        $(".loading").hide();
                    }
                }
            });
        });

        $(".add-program").click(function() {
            var id = $(this).attr("data-id");
            window.location.href = "<?php echo base_url("skpd/Bidang/tambah_program?id="); ?>" + id;
        });

        $("#btn_filter").click(function() {
            var tahun = $("#tahun").val();
            var url = "tahun=" + tahun;
            window.location.href = "<?php echo base_url("skpd/Bidang?"); ?>" + url;
        });
    });
</script>